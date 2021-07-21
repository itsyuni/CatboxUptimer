<?php

namespace Altum\Controllers;

use Altum\Date;
use Altum\Logger;
use Altum\Models\User;

class WebhookPaypal extends Controller {

    public function index() {

        $payload = @file_get_contents('php://input');
        $data = json_decode($payload);

        if($payload && $data && $data->event_type == 'PAYMENT.SALE.COMPLETED') {

            /* Initiate paypal */
            $paypal = new \PayPal\Rest\ApiContext(new \PayPal\Auth\OAuthTokenCredential(settings()->paypal->client_id, settings()->paypal->secret));
            $paypal->setConfig(['mode' => settings()->paypal->mode]);

            /* Get the billing agreement */
            try {
                $agreement = \PayPal\Api\Agreement::get($data->resource->billing_agreement_id, $paypal);
            } catch (Exception $exception) {

                /* Output errors properly */
                if (DEBUG) {
                    error_log($exception->getCode());
                    error_log($exception->getData());
                }

                http_response_code(400);

            }

            /* Get the needed details for the processing */
            $payer_info = $agreement->getPayer()->getPayerInfo();
            $payer_email = $payer_info->getEmail();
            $payer_name = $payer_info->getFirstName() . ' ' . $payer_info->getLastName();
            $payer_id = $payer_info->getPayerId();
            $subscription_id = $agreement->getId();

            $payment_id = $data->resource->id;
            $payment_total = $data->resource->amount->total;
            $payment_currency = $data->resource->amount->currency;
            $payment_subscription_id = 'paypal###' . $subscription_id;
            $payment_type = 'recurring';

            /* Try to explode first with the old method */
            $extra = explode('###', $agreement->getDescription());

            if(isset($extra[0], $extra[1], $extra[2])) {
                $user_id = (int) $extra[0];
                $plan_id = (int) $extra[1];
                $payment_frequency = $extra[2];
                $code = $extra[3];
                $discount_amount = 0;
                $base_amount = 0;
            } else {

                /* New method */
                $extra = explode('!!', $agreement->getDescription());

                $user_id = (int) $extra[0];
                $plan_id = (int) $extra[1];
                $base_amount = $extra[2];
                $payment_frequency = $extra[3];
                $code = $extra[4];
                $discount_amount = $extra[5] ? $extra[5] : 0;
                $taxes_ids = $extra[6];
            }

            /* Get the plan details */
            $plan = db()->where('plan_id', $plan_id)->getOne('plans');

            /* Just make sure the plan is still existing */
            if(!$plan) {
                http_response_code(400);
                die();
            }

            /* Make sure the transaction is not already existing */
            if(db()->where('payment_id', $payment_id)->where('processor', 'stripe')->has('payments')) {
                http_response_code(400);
                die();
            }

            /* Make sure the account still exists */
            $user = db()->where('user_id', $user_id)->getOne('users', ['user_id', 'email', 'name', 'payment_subscription_id', 'billing', 'referred_by', 'referred_by_has_converted']);

            if(!$user) {
                http_response_code(400);
                die();
            }

            /* Unsubscribe from the previous plan if needed */
            if(!empty($user->payment_subscription_id) && $user->payment_subscription_id != $payment_subscription_id) {
                try {
                    (new User())->cancel_subscription($user_id);
                } catch (\Exception $exception) {

                    /* Output errors properly */
                    if (DEBUG) {
                        echo $exception->getCode() . '-' . $exception->getMessage();

                        die();
                    }
                }
            }

            /* Make sure the code exists */
            $codes_code = db()->where('code', $code)->where('type', 'discount')->getOne('codes');

            if($codes_code) {
                $code = $codes_code->code;

                /* Check if we should insert the usage of the code or not */
                if(!db()->where('user_id', $this->user->user_id)->where('code_id', $codes_code->code_id)->has('redeemed_codes')) {

                    /* Update the code usage */
                    db()->where('code_id', $codes_code->code_id)->update('codes', ['redeemed' => db()->inc()]);

                    /* Add log for the redeemed code */
                    db()->insert('redeemed_codes', [
                        'code_id'   => $codes_code->code_id,
                        'user_id'   => $user_id,
                        'date'      => \Altum\Date::$date
                    ]);
                }
            }

            /* Add a log into the database */
            $payment_id = db()->insert('payments', [
                    'user_id' => $user_id,
                    'plan_id' => $plan_id,
                    'processor' => 'paypal',
                    'type' => $payment_type,
                    'frequency' => $payment_frequency,
                    'code' => $code,
                    'discount_amount' => $discount_amount,
                    'base_amount' => $base_amount,
                    'email' => $payer_email,
                    'payment_id' => $payment_id,
                    'subscription_id' => $subscription_id,
                    'payer_id' => $payer_id,
                    'name' => $payer_name,
                    'billing' => settings()->payment->taxes_and_billing_is_enabled && $user->billing ? $user->billing : null,
                    'taxes_ids' => !empty($taxes_ids) ? $taxes_ids : null,
                    'total_amount' => $payment_total,
                    'currency' => $payment_currency,
                    'date' => \Altum\Date::$date
            ]);

            /* Update the user with the new plan */
            $current_plan_expiration_date = $plan_id == $user->user_id ? $user->plan_expiration_date : '';
            switch($payment_frequency) {
                case 'monthly':
                    $plan_expiration_date = (new \DateTime($current_plan_expiration_date))->modify('+30 days')->format('Y-m-d H:i:s');
                    break;

                case 'annual':
                    $plan_expiration_date = (new \DateTime($current_plan_expiration_date))->modify('+12 months')->format('Y-m-d H:i:s');
                    break;

                case 'lifetime':
                    $plan_expiration_date = (new \DateTime($current_plan_expiration_date))->modify('+100 years')->format('Y-m-d H:i:s');
                    break;
            }

            /* Database query */
            db()->where('user_id', $user_id)->update('users', [
                'plan_id' => $plan_id,
                'plan_settings' => $plan->settings,
                'plan_expiration_date' => $plan_expiration_date,
                'payment_subscription_id' => $payment_subscription_id
            ]);

            /* Clear the cache */
            \Altum\Cache::$adapter->deleteItemsByTag('user_id=' . $user_id);

            /* Send notification to the user */
            $email_template = get_email_template(
                [],
                language()->global->emails->user_payment->subject,
                [
                    '{{NAME}}' => $user->name,
                    '{{PLAN_EXPIRATION_DATE}}' => Date::get($plan_expiration_date, 2),
                    '{{USER_PLAN_LINK}}' => url('account-plan'),
                    '{{USER_PAYMENTS_LINK}}' => url('account-payments'),
                ],
                language()->global->emails->user_payment->body
            );

            send_mail($user->email, $email_template->subject, $email_template->body);

            /* Send notification to admin if needed */
            if(settings()->email_notifications->new_payment && !empty(settings()->email_notifications->emails)) {

                $email_template = get_email_template(
                    [
                        '{{PROCESSOR}}' => 'paypal',
                        '{{TOTAL_AMOUNT}}' => $payment_total,
                        '{{CURRENCY}}' => $payment_currency,
                    ],
                    language()->global->emails->admin_new_payment_notification->subject,
                    [
                        '{{PROCESSOR}}' => 'paypal',
                        '{{TOTAL_AMOUNT}}' => $payment_total,
                        '{{CURRENCY}}' => $payment_currency,
                        '{{NAME}}' => $user->email,
                        '{{EMAIL}}' => $user->email,
                    ],
                    language()->global->emails->admin_new_payment_notification->body
                );

                send_mail(explode(',', settings()->email_notifications->emails), $email_template->subject, $email_template->body,);

            }

            /* Affiliate */
            if(settings()->affiliate->is_enabled && $this->user->referred_by) {
                if((settings()->affiliate->commission_type == 'once' && !$user->referred_by_has_converted) || settings()->affiliate->commission_type == 'forever') {

                    $referral_user = db()->where('user_id', $user->referred_by)->getOne('users', ['user_id', 'email', 'active']);

                    /* Make sure the referral user is active and existing */
                    if($referral_user && $referral_user->active == 1) {

                        $amount = number_format($payment_total * (float) settings()->affiliate->commission_percentage / 100, 2, '.', '');

                        /* Insert the affiliate commission */
                        db()->insert('affiliates_commissions', [
                            'user_id' => $referral_user->user_id,
                            'referred_user_id' => $user->user_id,
                            'payment_id' => $payment_id,
                            'amount' => $amount,
                            'currency' => settings()->payment->currency,
                            'datetime' => \Altum\Date::$date
                        ]);

                        /* Update the referred user */
                        db()->where('user_id', $user->user_id)->update('users', ['referred_by_has_converted' => 1]);

                    }

                }
            }

            http_response_code(200);
        }

    }

}

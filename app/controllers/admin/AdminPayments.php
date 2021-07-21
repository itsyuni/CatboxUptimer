<?php

namespace Altum\Controllers;

use Altum\Alerts;
use Altum\Database\Database;
use Altum\Date;
use Altum\Logger;
use Altum\Middlewares\Authentication;
use Altum\Middlewares\Csrf;

class AdminPayments extends Controller {

    public function index() {

        /* Prepare the filtering system */
        $filters = (new \Altum\Filters(['status', 'plan_id', 'user_id', 'type', 'processor', 'frequency'], ['name', 'email'], ['total_amount', 'email', 'date', 'name']));

        /* Prepare the paginator */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `payments` WHERE 1 = 1 {$filters->get_sql_where()}")->fetch_object()->total ?? 0;
        $paginator = (new \Altum\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('admin/payments?' . $filters->get_get() . '&page=%d')));

        /* Get the data */
        $payments = [];
        $payments_result = database()->query("
            SELECT
                `payments`.*, `users`.`name` AS `user_name`, `users`.`email` AS `user_email`
            FROM
                `payments`
            LEFT JOIN
                `users` ON `payments`.`user_id` = `users`.`user_id`
            WHERE
                1 = 1
                {$filters->get_sql_where('payments')}
                {$filters->get_sql_order_by('payments')}

            {$paginator->get_sql_limit()}
        ");
        while($row = $payments_result->fetch_object()) {
            $payments[] = $row;
        }

        /* Export handler */
        process_export_json($payments, 'include', ['id', 'user_id', 'plan_id', 'payment_id', 'subscription_id', 'payer_id', 'email', 'name', 'processor', 'type', 'frequency', 'billing', 'taxes_ids', 'base_amount', 'code', 'discount_amount', 'total_amount', 'currency', 'status', 'date']);
        process_export_csv($payments, 'include', ['id', 'user_id', 'plan_id', 'payment_id', 'subscription_id', 'payer_id', 'email', 'name', 'processor', 'type', 'frequency', 'base_amount', 'code', 'discount_amount', 'total_amount', 'currency', 'status', 'date']);

        /* Requested plan details */
        $plans = [];
        $plans_result = database()->query("SELECT `plan_id`, `name` FROM `plans`");
        while($row = $plans_result->fetch_object()) {
            $plans[$row->plan_id] = $row;
        }

        /* Prepare the pagination view */
        $pagination = (new \Altum\Views\View('partials/pagination', (array) $this))->run(['paginator' => $paginator]);

        /* Delete Modal */
        $view = new \Altum\Views\View('admin/payments/payment_delete_modal', (array) $this);
        \Altum\Event::add_content($view->run(), 'modals');

        /* Approve Modal */
        $view = new \Altum\Views\View('admin/payments/payment_approve_modal', (array) $this);
        \Altum\Event::add_content($view->run(), 'modals');

        /* Main View */
        $data = [
            'payments' => $payments,
            'plans' => $plans,
            'pagination' => $pagination,
            'filters' => $filters
        ];

        $view = new \Altum\Views\View('admin/payments/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }


    public function delete() {

        $payment_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        if(!Csrf::check('global_token')) {
            Alerts::add_error(language()->global->error_message->invalid_csrf_token);
            redirect('admin/users');
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            $payment = db()->where('id', $payment_id)->getOne('payments', ['payment_proof']);

            /* Delete the saved proof, if any */
            if($payment->payment_proof) {
                unlink(UPLOADS_PATH . 'offline_payment_proofs/' . $payment->payment_proof);
            }

            /* Delete the payment */
            db()->where('id', $payment_id)->delete('payments');

            /* Set a nice success message */
            Alerts::add_success(language()->admin_payment_delete_modal->success_message);

        }

        redirect('admin/payments');
    }

    public function approve() {

        $payment_id = (isset($this->params[0])) ? (int) $this->params[0] : null;

        if(!Csrf::check('global_token')) {
            Alerts::add_error(language()->global->error_message->invalid_csrf_token);
            redirect('admin/users');
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            /* details about the payment */
            $payment = db()->where('id', $payment_id)->getOne('payments', ['plan_id', 'user_id', 'frequency', 'email', 'code', 'payment_proof', 'payer_id', 'total_amount']);

            /* details about the user who paid */
            $user = db()->where('user_id', $payment_id->user_id)->getOne('users', ['user_id', 'referred_by', 'referred_by_has_converted']);

            /* plan that the user has paid for */
            $plan = (new \Altum\Models\Plan())->get_plan_by_id($payment->plan_id);

            /* Make sure the code that was potentially used exists */
            $codes_code = db()->where('code', $payment->code)->where('type', 'discount')->getOne('codes');

            if($codes_code) {
                /* Check if we should insert the usage of the code or not */
                if(!db()->where('user_id', $payment->user_id)->where('code_id', $codes_code->code_id)->has('redeemed_codes')) {

                    /* Update the code usage */
                    db()->where('code_id', $codes_code->code_id)->update('codes', ['redeemed' => db()->inc()]);

                    /* Add log for the redeemed code */
                    db()->insert('redeemed_codes', [
                        'code_id'   => $codes_code->code_id,
                        'user_id'   => $this->user->user_id,
                        'date'      => \Altum\Date::$date
                    ]);
                }
            }

            /* Give the plan to the user */
            switch($payment->frequency) {
                case 'monthly':
                    $plan_expiration_date = (new \DateTime())->modify('+30 days')->format('Y-m-d H:i:s');
                    break;

                case 'annual':
                    $plan_expiration_date = (new \DateTime())->modify('+12 months')->format('Y-m-d H:i:s');
                    break;

                case 'lifetime':
                    $plan_expiration_date = (new \DateTime())->modify('+100 years')->format('Y-m-d H:i:s');
                    break;
            }

            /* Database query */
            db()->where('user_id', $user->user_id)->update('users', [
                'plan_id' => $payment->plan_id,
                'plan_settings' => json_encode($plan->settings),
                'plan_expiration_date' => $plan_expiration_date
            ]);

            /* Clear the cache */
            \Altum\Cache::$adapter->deleteItemsByTag('user_id=' . $user->user_id);

            /* Send notification to the user */
            /* Prepare the email */
            $email_template = get_email_template(
                [],
                language()->global->emails->user_payment->subject,
                [
                    '{{PLAN_EXPIRATION_DATE}}' => Date::get($plan_expiration_date, 2),
                    '{{USER_PLAN_LINK}}' => url('account-plan'),
                    '{{USER_PAYMENTS_LINK}}' => url('account-payments'),
                ],
                language()->global->emails->user_payment->body
            );

            send_mail(
                $payment->email,
                $email_template->subject,
                $email_template->body
            );

            /* Update the payment */
            db()->where('id', $payment_id)->update('payments', ['status' => 1]);

            /* Affiliate */
            if(settings()->affiliate->is_enabled && $user->referred_by) {
                if((settings()->affiliate->commission_type == 'once' && !$user->referred_by_has_converted) || settings()->affiliate->commission_type == 'forever') {

                    $referral_user = db()->where('user_id', $user->referred_by)->getOne('users', ['user_id', 'email', 'active']);

                    /* Make sure the referral user is active and existing */
                    if($referral_user && $referral_user->active == 1) {

                        $amount = number_format($payment->total_amount * (float) settings()->affiliate->commission_percentage / 100, 2, '.', '');

                        /* Insert the affiliate commission */
                        db()->insert('affiliates_commissions', [
                            'user_id' => $referral_user->user_id,
                            'referred_user_id' => $user->user_id,
                            'payment_id' => $payment_id,
                            'amount' => $amount,
                            'currency' => settings()->payment->currency,
                            'datetime' => \Altum\Date::$date,
                        ]);

                        /* Update the referred user */
                        db()->where('user_id', $user->user_id)->update('users', ['referred_by_has_converted' => 1]);

                    }

                }
            }

            /* Set a nice success message */
            Alerts::add_success(language()->admin_payment_approve_modal->success_message);

        }

        redirect('admin/payments');
    }
}

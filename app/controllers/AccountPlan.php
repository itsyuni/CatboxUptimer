<?php

namespace Altum\Controllers;

use Altum\Alerts;
use Altum\Logger;
use Altum\Middlewares\Authentication;
use Altum\Middlewares\Csrf;
use Altum\Models\Plan;
use Altum\Models\User;
use Altum\Response;

class AccountPlan extends Controller {

    public function index() {

        Authentication::guard();

        /* Establish the account sub menu view */
        $menu = new \Altum\Views\View('partials/app_sub_menu', (array) $this);
        $this->add_view_content('app_sub_menu', $menu->run());

        /* Prepare the View */
        $view = new \Altum\Views\View('account-plan/index', (array) $this);

        $this->add_view_content('content', $view->run());

    }

    public function redeem_code() {
        Authentication::guard();

        if(!settings()->payment->is_enabled || !settings()->payment->codes_is_enabled) {
            redirect('account-plan');
        }

        if(empty($_POST)) {
            redirect('account-plan');
        }

        if(!Csrf::check()) {
            Alerts::add_error(language()->global->error_message->invalid_csrf_token);
            redirect('account-plan');
        }

        /* Make sure the discount code exists */
        $code = db()->where('code', $_POST['code'])->where('type', 'redeemable')->getOne('codes');

        if(!$code) {
            Alerts::add_field_error('code', language()->account_plan->error_message->code_invalid);
            redirect('account-plan');
        }

        /* Make sure the plan id exists and get details about it */
        $plan = (new Plan())->get_plan_by_id($code->plan_id);

        if(!$plan) {
            Alerts::add_field_error('code', language()->account_plan->error_message->code_invalid);
            redirect('account-plan');
        }

        /* Make sure the code was not used previously */
        if(db()->where('user_id', $this->user->user_id)->where('code_id', $code->code_id)->getOne('redeemed_codes', ['id'])) {
            Alerts::add_field_error('code', language()->account_plan->error_message->code_used);
            redirect('account-plan');
        }

        /* Cancel current subscription */
        try {
            (new User(['user' => $this->user]))->cancel_subscription();
        } catch (\Exception $exception) {

            /* Output errors properly */
            if(DEBUG) {
                echo $exception->getCode() . '-' . $exception->getMessage();

                die();
            } else {

                Alerts::add_error($exception->getMessage());
                redirect('account-plan');

            }
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            $plan_expiration_date = (new \DateTime())->modify('+' . $code->days . ' days')->format('Y-m-d H:i:s');
            $plan_settings = json_encode($plan->settings);

            /* Database query */
            db()->where('user_id', $this->user->user_id)->update('users', [
                'plan_id' => $plan->plan_id,
                'plan_expiration_date' => $plan_expiration_date,
                'plan_settings' => $plan_settings
            ]);

            /* Update the code usage */
            db()->where('code_id', $code->code_id)->update('codes', ['redeemed' => db()->inc()]);

            /* Add log for the redeemed code */
            db()->insert('redeemed_codes', [
                'code_id'   => $code->code_id,
                'user_id'   => $this->user->user_id,
                'date'      => \Altum\Date::$date
            ]);

            /* Clear the cache */
            \Altum\Cache::$adapter->deleteItemsByTag('user_id=' . $this->user->user_id);

            /* Set a nice success message */
            Alerts::add_success(language()->account_plan->success_message->code_redeemed);

            redirect('account-plan');
        }
    }

    /* Ajax to check if redemption codes are available */
    public function code() {
        Authentication::guard();

        $_POST = json_decode(file_get_contents('php://input'), true);

        if(!Csrf::check('global_token')) {
            die();
        }

        if(empty($_POST)) {
            die();
        }

        if(!settings()->payment->is_enabled || !settings()->payment->codes_is_enabled) {
            die();
        }

        /* Make sure the discount code exists */
        $code = db()->where('code', $_POST['code'])->where('type', 'redeemable')->where('redeemed < quantity')->getOne('codes');

        if(!$code) {
            Response::json(language()->account_plan->error_message->code_invalid, 'error');
        }

        /* Make sure the plan id exists and get details about it */
        $plan = (new Plan())->get_plan_by_id($code->plan_id);

        if(!$plan) {
            Response::json(language()->account_plan->error_message->code_invalid, 'error');
        }

        /* Make sure the code was not used previously */
        if(db()->where('user_id', $this->user->user_id)->where('code_id', $code->code_id)->getOne('redeemed_codes', ['id'])) {
            Response::json(language()->account_plan->error_message->code_used, 'error');
        }

        Response::json(sprintf(language()->account_plan->success_message->code, '<strong>' . $plan->name . '</strong>', '<strong>' . $code->days . '</strong>'), 'success', ['discount' => $code->discount]);
    }
}

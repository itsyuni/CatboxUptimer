<?php

namespace Altum\Controllers;

use Altum\Alerts;
use Altum\Middlewares\Csrf;
use Altum\Models\User;

class AdminPlans extends Controller {

    public function index() {

        /* Delete Modal */
        $view = new \Altum\Views\View('admin/plans/plan_delete_modal', (array) $this);
        \Altum\Event::add_content($view->run(), 'modals');

        $plans = db()->orderBy('`order`')->get('plans');

        /* Main View */
        $data = [
            'plans' => $plans
        ];

        $view = new \Altum\Views\View('admin/plans/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function delete() {

        $plan_id = isset($this->params[0]) ? $this->params[0] : null;

        if(!Csrf::check('global_token')) {
            Alerts::add_error(language()->global->error_message->invalid_csrf_token);
            redirect('admin/plans');
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            /* Get all the users with this plan that have subscriptions and cancel them */
            $result = database()->query("SELECT `user_id`, `payment_subscription_id` FROM `users` WHERE `plan_id` = {$plan_id} AND `payment_subscription_id` <> ''");

            while($row = $result->fetch_object()) {
                try {
                    (new User(['user' => $row]))->cancel_subscription();
                } catch (\Exception $exception) {

                    /* Output errors properly */
                    if(DEBUG) {
                        echo $exception->getCode() . '-' . $exception->getMessage();

                        die();
                    }

                }

                /* Change the user plan to custom and leave their current features they paid for on */
                db()->where('user_id', $row->user_id)->update('users', ['plan_id' => 'custom']);

                /* Clear the cache */
                \Altum\Cache::$adapter->deleteItemsByTag('user_id=' . $row->user_id);
            }

            /* Delete the plan */
            db()->where('plan_id', $plan_id)->delete('plans');

            /* Set a nice success message */
            Alerts::add_success(language()->admin_plan_delete_modal->success_message);

        }

        redirect('admin/plans');
    }

}

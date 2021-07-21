<?php

namespace Altum\Controllers;

use Altum\Models\User;
use Altum\Response;
use Altum\Traits\Apiable;

class AdminApiPlans extends Controller {
    use Apiable;

    public function index() {

        $this->verify_request(true);

        /* Decide what to continue with */
        switch($_SERVER['REQUEST_METHOD']) {
            case 'GET':

                /* Detect if we only need an object, or the whole list */
                if(isset($this->params[0])) {
                    $this->get();
                } else {
                    $this->get_all();
                }

                break;
        }

        $this->return_404();

    }

    private function get_all() {

        /* Get the data */
        $data = [];

        foreach(['free', 'trial'] as $plan) {
            $data[] = [
                'id' => settings()->{'plan_' . $plan}->plan_id,
                'name' => settings()->{'plan_' . $plan}->name,
                'days' => settings()->{'plan_' . $plan}->days,
                'status' => settings()->{'plan_' . $plan}->status,
                'settings' => settings()->{'plan_' . $plan}->settings,
            ];
        }

        $data_result = database()->query("SELECT * FROM `plans`");
        while($row = $data_result->fetch_object()) {

            /* Prepare the data */
            $row = [
                'id' => (int) $row->plan_id,

                'name' => $row->name,
                'monthly_price' => (float) $row->monthly_price,
                'annual_price' => (float) $row->annual_price,
                'lifetime_price' => (float) $row->lifetime_price,
                'settings' => json_decode($row->settings),
                'taxes_ids' => json_decode($row->taxes_ids),
                'status' => (int) $row->status,
                'date' => $row->date,
            ];

            $data[] = $row;
        }

        Response::jsonapi_success($data);
    }

    private function get() {

        $plan_id = isset($this->params[0]) ? $this->params[0] : null;

        /* Try to get details about the resource id */
        switch($plan_id) {
            case 'free':
            case 'trial':
                $plan = settings()->{'plan_' . $plan_id};
            break;

            default:
                $plan = db()->where('plan_id', $plan_id)->getOne('plans');
            break;
        }

        /* We haven't found the resource */
        if(!$plan) {
            Response::jsonapi_error([[
                'title' => language()->api->error_message->not_found,
                'status' => '404'
            ]], null, 404);
        }

        /* Prepare the data */
        if(in_array($plan->plan_id, ['free', 'trial'])) {
            $data[] = [
                'id' => settings()->{'plan_' . $plan->plan_id}->plan_id,
                'name' => settings()->{'plan_' . $plan->plan_id}->name,
                'days' => settings()->{'plan_' . $plan->plan_id}->days,
                'status' => settings()->{'plan_' . $plan->plan_id}->status,
                'settings' => settings()->{'plan_' . $plan->plan_id}->settings,
            ];
        } else {
            $data = [
                'id' => (int) $plan->plan_id,

                'name' => $plan->name,
                'monthly_price' => (float) $plan->monthly_price,
                'annual_price' => (float) $plan->annual_price,
                'lifetime_price' => (float) $plan->lifetime_price,
                'settings' => json_decode($plan->settings),
                'taxes_ids' => json_decode($plan->taxes_ids),
                'status' => (int) $plan->status,
                'date' => $plan->date,
            ];
        }

        Response::jsonapi_success($data);

    }

}

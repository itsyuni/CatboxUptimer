<?php

namespace Altum\Models;

use Altum\Database\Database;
use Altum\Logger;
use MaxMind\Db\Reader;

class User extends Model {

    public function get_user_by_user_id($user_id) {

        /* Try to check if the store posts exists via the cache */
        $cache_instance = \Altum\Cache::$adapter->getItem('user?user_id=' . $user_id);

        /* Set cache if not existing */
        if(is_null($cache_instance->get())) {

            /* Get data from the database */
            $data = db()->where('user_id', $user_id)->getOne('users');

            if($data) {

                /* Parse the users plan settings */
                $data->plan_settings = json_decode($data->plan_settings);

                /* Parse billing details if existing */
                $data->billing = json_decode($data->billing);

                /* Save to cache */
                \Altum\Cache::$adapter->save(
                    $cache_instance->set($data)->expiresAfter(86400)->addTag('users')->addTag('user_id=' . $data->user_id)
                );
            }

        } else {

            /* Get cache */
            $data = $cache_instance->get();

        }

        return $data;
    }

    public function get_user_by_user_id_and_token_code($user_id, $token_code) {

        /* Try to check if the store posts exists via the cache */
        $cache_instance = \Altum\Cache::$adapter->getItem('user?user_id=' . md5($user_id) . '&token_code=' . $token_code);

        /* Set cache if not existing */
        if(is_null($cache_instance->get())) {

            /* Get data from the database */
            $data = db()->where('user_id', $user_id)->where('token_code', $token_code)->getOne('users');

            if($data) {

                /* Parse the users plan settings */
                $data->plan_settings = json_decode($data->plan_settings);

                /* Parse billing details if existing */
                $data->billing = json_decode($data->billing);

                /* Save to cache */
                \Altum\Cache::$adapter->save(
                    $cache_instance->set($data)->expiresAfter(86400)->addTag('users')->addTag('user_id=' . $data->user_id)
                );
            }

        } else {

            /* Get cache */
            $data = $cache_instance->get();

        }

        return $data;
    }

    public function delete($user_id) {

        /* Cancel his active subscriptions if active */
        $this->cancel_subscription($user_id);

        /* Send webhook notification if needed */
        if(settings()->webhooks->user_delete) {

            $user = db()->where('user_id', $user_id)->getOne('users', ['user_id', 'email', 'name']);

            \Unirest\Request::post(settings()->webhooks->user_delete, [], [
                'user_id' => $user->user_id,
                'email' => $user->email,
                'name' => $user->name
            ]);

        }

        /* Delete everything related to the status pages that the user owns */
        $result = database()->query("SELECT `status_page_id`, `logo`, `favicon` FROM `status_pages` WHERE `user_id` = {$user_id}");

        while($status_page = $result->fetch_object()) {

            (new \Altum\Models\StatusPage())->delete_uploads($status_page->favicon, $status_page->logo);
            (new \Altum\Models\StatusPage())->delete($status_page->status_page_id);

        }

        /* Delete the record from the database */
        db()->where('user_id', $user_id)->delete('users');

        /* Clear the cache */
        \Altum\Cache::$adapter->deleteItemsByTag('user_id=' . $user_id);

    }

    public function update_last_activity($user_id) {
        db()->where('user_id', $user_id)->update('users', ['last_activity' => \Altum\Date::$date]);
    }

    public function create(
        $email = '',
        $raw_password = '',
        $name = '',
        $active = 0,
        $email_activation_code = null,
        $facebook_id = null,
        $plan_id = 'free',
        $plan_settings = '',
        $plan_expiration_date = null,
        $timezone = 'UTC',
        $is_admin_created = false
    ) {

        /* Define some needed variables */
        $password = password_hash($raw_password, PASSWORD_DEFAULT);
        $total_logins = $active == '1' && !$is_admin_created ? 1 : 0;
        $plan_expiration_date = $plan_expiration_date ?? \Altum\Date::$date;
        $plan_trial_done = $plan_id == 'trial' ? 1 : 0;
        $language = \Altum\Language::$default_language;
        $billing = json_encode(['type' => 'personal', 'name' => '', 'address' => '', 'city' => '', 'county' => '', 'zip' => '', 'country' => '', 'phone' => '', 'tax_id' => '',]);
        $api_key = md5($email . microtime() . microtime());
        $referral_key = md5(rand() . $email . microtime() . $email. microtime());
        $ip = $is_admin_created ? null : get_ip();
        $last_user_agent = $is_admin_created ? null : Database::clean_string($_SERVER['HTTP_USER_AGENT']);

        /* Detect the location */
        try {
            $maxmind = $is_admin_created ? null : (new Reader(APP_PATH . 'includes/GeoLite2-Country.mmdb'))->get($ip);
        } catch(\Exception $exception) {
            /* :) */
        }
        $country = isset($maxmind) && isset($maxmind['country']) ? $maxmind['country']['iso_code'] : null;

        /* Check for potential referral cookie */
        $referred_by = null;
        if(!$is_admin_created && isset($_COOKIE['referred_by']) && $user = db()->where('referral_key', $_COOKIE['referred_by'])->getOne('users', ['user_id', 'referral_key'])) {
            $referred_by = $user->user_id;
        }

        /* Add the user to the database */
        $registered_user_id = db()->insert('users', [
            'password' => $password,
            'email' => $email,
            'name' => $name,
            'billing' => $billing,
            'api_key' => $api_key,
            'facebook_id' => $facebook_id,
            'email_activation_code' => $email_activation_code,
            'plan_id' => $plan_id,
            'plan_expiration_date' => $plan_expiration_date,
            'plan_settings' => $plan_settings,
            'plan_trial_done' => $plan_trial_done,
            'referral_key' => $referral_key,
            'referred_by' => $referred_by,
            'language' => $language,
            'timezone' => $timezone,
            'active' => $active,
            'date' => \Altum\Date::$date,
            'ip' => $ip,
            'country' => $country,
            'last_user_agent' => $last_user_agent,
            'total_logins' => $total_logins,
        ]);

        /* Clear out referral cookie if needed */
        if($referred_by) {
            setcookie('referred_by', '', time()-30, COOKIE_PATH);
        }

        return $registered_user_id;
    }

    /*
     * Function to update a user with more details on a login action
     */
    public function login_aftermath_update($user_id) {

        $ip = get_ip();
        $last_user_agent = Database::clean_string($_SERVER['HTTP_USER_AGENT']);

        /* Detect the location */
        try {
            $maxmind = (new Reader(APP_PATH . 'includes/GeoLite2-Country.mmdb'))->get($ip);
        } catch(\Exception $exception) {
            /* :) */
        }
        $country = isset($maxmind) && isset($maxmind['country']) ? $maxmind['country']['iso_code'] : null;

        /* Database query */
        db()->where('user_id', $user_id)->update('users', [
            'ip' => $ip,
            'country' => $country,
            'last_user_agent' => $last_user_agent,
            'total_logins' => db()->inc()
        ]);

        Logger::users($user_id, 'login.success');

        /* Clear the cache */
        \Altum\Cache::$adapter->deleteItemsByTag('user_id=' . $user_id);

    }

    /*
     * Needs to have access to the Settings and the User variable, or pass in the user_id variable
     */
    public function cancel_subscription($user_id = false) {

        if(!isset($this->user) && !$user_id) {
            throw new \Exception('Model needs to have access to the "user" variable or pass in the $user_in.');
        }

        if($user_id) {
            $this->user = db()->where('user_id', $user_id)->getOne('users', ['user_id', 'payment_subscription_id']);
        }

        if(empty($this->user->payment_subscription_id)) {
            return true;
        }

        $data = explode('###', $this->user->payment_subscription_id);
        $type = $data[0];
        $subscription_id = $data[1];

        switch($type) {
            case 'stripe':

                /* Initiate Stripe */
                \Stripe\Stripe::setApiKey(settings()->stripe->secret_key);

                /* Cancel the Stripe Subscription */
                $subscription = \Stripe\Subscription::retrieve($subscription_id);
                $subscription->cancel();

                break;

            case 'paypal':

                /* Initiate paypal */
                $paypal = new \PayPal\Rest\ApiContext(new \PayPal\Auth\OAuthTokenCredential(settings()->paypal->client_id, settings()->paypal->secret));
                $paypal->setConfig(['mode' => settings()->paypal->mode]);

                /* Create an Agreement State Descriptor, explaining the reason to suspend. */
                $agreement_state_descriptior = new \PayPal\Api\AgreementStateDescriptor();
                $agreement_state_descriptior->setNote('Suspending the agreement');

                /* Get details about the executed agreement */
                $agreement = \PayPal\Api\Agreement::get($subscription_id, $paypal);

                /* Suspend */
                $agreement->suspend($agreement_state_descriptior, $paypal);


                break;
        }

        /* Database query */
        db()->where('user_id', $this->user->user_id)->update('users', ['payment_subscription_id' => '']);

        /* Clear the cache */
        \Altum\Cache::$adapter->deleteItemsByTag('user_id=' . $this->user->user_id);

    }

}

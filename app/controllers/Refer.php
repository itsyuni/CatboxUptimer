<?php

namespace Altum\Controllers;

use Altum\Database\Database;
use Altum\Middlewares\Authentication;

class Refer extends Controller {

    public function index() {

        Authentication::guard('guest');

        if(!settings()->affiliate->is_enabled) {
            redirect();
        }

        $referral_key = isset($this->params[0]) ? Database::clean_string($this->params[0]) : null;

        /* Get the owner user of the referral key */
        if(!$user = db()->where('referral_key', $referral_key)->getOne('users', ['user_id', 'active', 'referral_key'])) {
            redirect();
        }

        /* Make sure the user is still active */
        if($user->active != 1) {
            redirect();
        }

        /* Set the cookie for 90 days */
        setcookie('referred_by', $user->referral_key, time()+60*60*24*90, COOKIE_PATH);

        /* Redirect to the landing page */
        redirect();

    }

}

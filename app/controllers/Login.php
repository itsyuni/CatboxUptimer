<?php

namespace Altum\Controllers;

use Altum\Alerts;
use Altum\Captcha;
use Altum\Database\Database;
use Altum\Logger;
use Altum\Middlewares\Authentication;
use Altum\Models\User;

class Login extends Controller {

    public function index() {

        Authentication::guard('guest');

        $method	= (isset($this->params[0])) ? $this->params[0] : null;
        $redirect = isset($_GET['redirect']) ? Database::clean_string($_GET['redirect']) : 'dashboard';

        /* Default values */
        $values = [
            'email' => isset($_GET['email']) ? Database::clean_string($_GET['email']) : '',
            'password' => '',
        ];

        /* Initiate captcha */
        $captcha = new Captcha();

        /* One time login */
        if($method == 'one-time-login-code') {
            $one_time_login_code = isset($this->params[1]) ? Database::clean_string($this->params[1]) : null;

            if(empty($one_time_login_code)) {
                redirect('login');
            }

            /* Try to get the user from the database */
            $user = db()->where('one_time_login_code', $one_time_login_code)->getOne('users', ['user_id', 'name', 'active']);

            if(!$user) {
                redirect('login');
            }

            if($user->active != 1) {
                Alerts::add_error(language()->login->error_message->user_not_active);
                redirect('login');
            }

            /* Login the user */
            $_SESSION['user_id'] = $user->user_id;

            (new User())->login_aftermath_update($user->user_id);

            /* Remove one time login */
            db()->where('user_id', $user->user_id)->update('users', ['one_time_login_code' => null]);

            /* Set a welcome message */
            Alerts::add_info(sprintf(language()->login->info_message->logged_in, $user->name));

            redirect($redirect);
        }

        /* Facebook Login / Register */
        if(settings()->facebook->is_enabled && !empty(settings()->facebook->app_id) && !empty(settings()->facebook->app_secret)) {

            $facebook = new \Facebook\Facebook([
                'app_id' => settings()->facebook->app_id,
                'app_secret' => settings()->facebook->app_secret,
                'default_graph_version' => 'v3.2',
            ]);

            $facebook_helper = $facebook->getRedirectLoginHelper();
            $facebook_login_url = $facebook->getRedirectLoginHelper()->getLoginUrl(SITE_URL . 'login/facebook', ['email', 'public_profile']);

            /* Check for the redirect after the oauth checkin */
            if($method == 'facebook') {
                try {
                    $facebook_access_token = $facebook_helper->getAccessToken(SITE_URL . 'login/facebook');
                } catch(\Facebook\Exceptions\FacebookResponseException $e) {
                    Alerts::add_error('Graph returned an error: ' . $e->getMessage());
                } catch(\Facebook\Exceptions\FacebookSDKException $e) {
                    Alerts::add_error('Facebook SDK returned an error: ' . $e->getMessage());
                }
            }

            if(isset($facebook_access_token)) {

                /* The OAuth 2.0 client handler helps us manage access tokens */
                $facebook_oAuth2_client = $facebook->getOAuth2Client();

                /* Get the access token metadata from /debug_token */
                $facebook_token_metadata = $facebook_oAuth2_client->debugToken($facebook_access_token);

                /* Validation */
                $facebook_token_metadata->validateAppId(settings()->facebook->app_id);
                $facebook_token_metadata->validateExpiration();

                if(!$facebook_access_token->isLongLived()) {
                    /* Exchanges a short-lived access token for a long-lived one */
                    try {
                        $facebook_access_token = $facebook_oAuth2_client->getLongLivedAccessToken($facebook_access_token);
                    } catch (\Facebook\Exceptions\FacebookSDKException $e) {
                        Alerts::add_error('Error getting long-lived access token: ' . $facebook_helper->getMessage());
                    }
                }

                try {
                    $response = $facebook->get('/me?fields=id,name,email', $facebook_access_token);
                } catch(\Facebook\Exceptions\FacebookResponseException $e) {
                    Alerts::add_error('Graph returned an error: ' . $e->getMessage());
                } catch(\Facebook\Exceptions\FacebookSDKException $e) {
                    Alerts::add_error('Facebook SDK returned an error: ' . $e->getMessage());
                }

                if(isset($response)) {
                    $facebook_user = $response->getGraphUser();
                    $facebook_user_id = $facebook_user->getId();
                    $email = $facebook_user->getEmail();
                    $name = $facebook_user->getName();

                    /* Check if email is actually not null */
                    if(is_null($email)) {
                        Alerts::add_error(language()->login->error_message->email_is_null);

                        redirect('login');
                    }

                    /* If the user is already in the system, log him in */
                    if($user = db()->where('email', $email)->getOne('users', ['user_id'])) {
                        $_SESSION['user_id'] = $user->user_id;

                        (new User())->login_aftermath_update($user->user_id);

                        redirect($redirect);
                    }

                    /* Create a new account */
                    else {

                        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                            /* Determine what plan is set by default */
                            $plan_id                    = 'free';
                            $plan_settings              = json_encode(settings()->plan_free->settings);
                            $plan_expiration_date       = \Altum\Date::$date;

                            /* When only the trial package is available make that the default one */
                            if(!settings()->plan_free->status && settings()->plan_trial->status) {
                                $plan_id                = 'trial';
                                $plan_settings          = json_encode(settings()->plan_trial->settings);
                                $plan_expiration_date   = (new \DateTime())->modify('+' . settings()->plan_trial->days . ' days')->format('Y-m-d H:i:s');
                            }

                            $registered_user_id = (new User())->create(
                                $email,
                                string_generate(8),
                                $name,
                                1,
                                null,
                                $facebook_user_id,
                                $plan_id,
                                $plan_settings,
                                $plan_expiration_date,
                                settings()->default_timezone
                            );

                            /* Log the action */
                            Logger::users($registered_user_id, 'register.facebook_success');

                            /* Send notification to admin if needed */
                            if(settings()->email_notifications->new_user && !empty(settings()->email_notifications->emails)) {

                                $email_template = get_email_template(
                                    [],
                                    language()->global->emails->admin_new_user_notification->subject,
                                    [
                                        '{{NAME}}' => $name,
                                        '{{EMAIL}}' => $email,
                                    ],
                                    language()->global->emails->admin_new_user_notification->body
                                );

                                send_mail(explode(',', settings()->email_notifications->emails), $email_template->subject, $email_template->body);

                            }

                            /* Send webhook notification if needed */
                            if(settings()->webhooks->user_new) {

                                \Unirest\Request::post(settings()->webhooks->user_new, [], [
                                    'user_id' => $registered_user_id,
                                    'email' => $email,
                                    'name' => $name
                                ]);

                            }

                            /* Set a nice success message */
                            Alerts::add_success(language()->register->success_message->login);

                            /* Log the user in and redirect him */
                            $_SESSION['user_id'] = $registered_user_id;

                            redirect($redirect);
                        }
                    }
                }
            }
        }

        if(!empty($_POST)) {
            /* Clean email and encrypt the password */
            $_POST['email'] = Database::clean_string($_POST['email']);
            $_POST['twofa_token'] = isset($_POST['twofa_token']) ? Database::clean_string($_POST['twofa_token']) : null;
            $values['email'] = $_POST['email'];
            $values['password'] = $_POST['password'];

            /* Check for any errors */
            $required_fields = ['email', 'password'];
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]))) {
                    Alerts::add_field_error($field, language()->global->error_message->empty_field);
                }
            }

            if(settings()->captcha->login_is_enabled && !$captcha->is_valid()) {
                Alerts::add_field_error('captcha', language()->global->error_message->invalid_captcha);
            }

            /* Try to get the user from the database */
            $user = db()->where('email', $_POST['email'])->getOne('users', ['user_id', 'email', 'name', 'active', 'password', 'token_code', 'twofa_secret']);

            if(!$user) {
                Alerts::add_error(language()->login->error_message->wrong_login_credentials);
            } else {

                if($user->active != 1) {
                    Alerts::add_error(language()->login->error_message->user_not_active);
                } else

                    if(!password_verify($_POST['password'], $user->password)) {
                        Logger::users($user->user_id, 'login.wrong_password');

                        Alerts::add_error(language()->login->error_message->wrong_login_credentials);
                    }

            }

            /* Check if the user has Two-factor Authentication enabled */
            if($user && $user->twofa_secret) {

                if($_POST['twofa_token']) {

                    $twofa = new \RobThree\Auth\TwoFactorAuth(settings()->title, 6, 30);
                    $twofa_check = $twofa->verifyCode($user->twofa_secret, $_POST['twofa_token']);

                    if(!$twofa_check) {
                        Alerts::add_field_error('twofa_token', language()->login->error_message->twofa_token);
                    }

                } else {

                    Alerts::add_info(language()->login->info_message->twofa_token);

                }

            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors() && !Alerts::has_infos()) {

                /* If remember me is checked, log the user with cookies for 30 days else, remember just with a session */
                if(isset($_POST['rememberme'])) {
                    $token_code = $user->token_code;

                    /* Generate a new token */
                    if(empty($user->token_code)) {
                        $token_code = md5($user->email . microtime());

                        db()->where('user_id', $user->user_id)->update('users', ['token_code' => $token_code]);
                    }

                    setcookie('user_id', $user->user_id, time()+60*60*24*30, COOKIE_PATH);
                    setcookie('token_code', $token_code, time()+60*60*24*30, COOKIE_PATH);

                } else {
                    $_SESSION['user_id'] = $user->user_id;
                }

                (new User())->login_aftermath_update($user->user_id);

                Alerts::add_info(sprintf(language()->login->info_message->logged_in, $user->name));

                redirect($redirect);
            }
        }

        /* Prepare the View */
        $data = [
            'captcha' => $captcha,
            'values' => $values,
            'facebook_login_url' => $facebook_login_url ?? null,
            'user' => $user ?? null
        ];

        $view = new \Altum\Views\View('login/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}

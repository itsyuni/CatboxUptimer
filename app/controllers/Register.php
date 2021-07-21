<?php

namespace Altum\Controllers;

use Altum\Alerts;
use Altum\Captcha;
use Altum\Database\Database;
use Altum\Logger;
use Altum\Middlewares\Authentication;
use Altum\Models\User;

class Register extends Controller {

    public function index() {

        /* Check if Registration is enabled first */
        if(!settings()->register_is_enabled) {
            redirect();
        }

        Authentication::guard('guest');

        $redirect = isset($_GET['redirect']) ? Database::clean_string($_GET['redirect']) : 'dashboard';

        /* Default variables */
        $values = [
            'name' => isset($_GET['name']) ? Database::clean_string($_GET['name']) : '',
            'email' => isset($_GET['email']) ? Database::clean_string($_GET['email']) : '',
            'password' => ''
        ];

        /* Initiate captcha */
        $captcha = new Captcha();

        /* Facebook Login / Register */
        if(settings()->facebook->is_enabled && !empty(settings()->facebook->app_id) && !empty(settings()->facebook->app_secret)) {
            $facebook = new \Facebook\Facebook([
                'app_id' => settings()->facebook->app_id,
                'app_secret' => settings()->facebook->app_secret,
                'default_graph_version' => 'v3.2',
            ]);

            $facebook_login_url = $facebook->getRedirectLoginHelper()->getLoginUrl(SITE_URL . 'login/facebook', ['email', 'public_profile']);
        }

        if(!empty($_POST)) {
            /* Clean some posted variables */
            $_POST['name'] = trim(filter_var($_POST['name'], FILTER_SANITIZE_STRING));
            $_POST['email'] = trim(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL));

            /* Default variables */
            $values['name'] = $_POST['name'];
            $values['email'] = $_POST['email'];
            $values['password'] = $_POST['password'];

            /* Check for any errors */
            $required_fields = ['name', 'email' ,'password'];
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]))) {
                    Alerts::add_field_error($field, language()->global->error_message->empty_field);
                }
            }

            if(settings()->captcha->register_is_enabled && !$captcha->is_valid()) {
                Alerts::add_field_error('captcha', language()->global->error_message->invalid_captcha);
            }
            if(mb_strlen($_POST['name']) < 3 || mb_strlen($_POST['name']) > 32) {
                Alerts::add_field_error('name', language()->register->error_message->name_length);
            }
            if(db()->where('email', $_POST['email'])->has('users')) {
                Alerts::add_field_error('email', language()->register->error_message->email_exists);
            }
            if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                Alerts::add_field_error('email', language()->register->error_message->invalid_email);
            }
            if(mb_strlen(trim($_POST['password'])) < 6) {
                Alerts::add_field_error('password', language()->register->error_message->short_password);
            }

            /* If there are no errors continue the registering process */
            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {
                /* Define some needed variables */
                $active 	                = (int) !settings()->email_confirmation;
                $email_code                 = md5($_POST['email'] . microtime());

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
                    $_POST['email'],
                    $_POST['password'],
                    $_POST['name'],
                    (int) !settings()->email_confirmation,
                    $email_code,
                    null,
                    $plan_id,
                    $plan_settings,
                    $plan_expiration_date,
                    settings()->default_timezone
                );

                /* Log the action */
                Logger::users($registered_user_id, 'register.success');

                /* Send notification to admin if needed */
                if(settings()->email_notifications->new_user && !empty(settings()->email_notifications->emails)) {

                    /* Prepare the email */
                    $email_template = get_email_template(
                        [],
                        language()->global->emails->admin_new_user_notification->subject,
                        [
                            '{{NAME}}' => $_POST['name'],
                            '{{EMAIL}}' => $_POST['email'],
                        ],
                        language()->global->emails->admin_new_user_notification->body
                    );

                    send_mail(explode(',', settings()->email_notifications->emails), $email_template->subject, $email_template->body);

                }

                /* If active = 1 then login the user, else send the user an activation email */
                if($active == '1') {

                    /* Send webhook notification if needed */
                    if(settings()->webhooks->user_new) {

                        \Unirest\Request::post(settings()->webhooks->user_new, [], [
                            'user_id' => $registered_user_id,
                            'email' => $_POST['email'],
                            'name' => $_POST['name']
                        ]);

                    }

                    /* Set a nice success message */
                    Alerts::add_success(language()->register->success_message->login);

                    $_SESSION['user_id'] = $registered_user_id;

                    Logger::users($registered_user_id, 'login.success');

                    redirect($redirect);
                } else {

                    /* Prepare the email */
                    $email_template = get_email_template(
                        [
                            '{{NAME}}' => $_POST['name'],
                        ],
                        language()->global->emails->user_activation->subject,
                        [
                            '{{ACTIVATION_LINK}}' => url('activate-user?email=' . md5($_POST['email']) . '&email_activation_code=' . $email_code . '&type=user_activation' . '&redirect=' . $redirect),
                            '{{NAME}}' => $_POST['name'],
                        ],
                        language()->global->emails->user_activation->body
                    );

                    send_mail($_POST['email'], $email_template->subject, $email_template->body);

                    /* Set a nice success message */
                    Alerts::add_success(language()->register->success_message->registration);
                }

            }
        }

        /* Main View */
        $data = [
            'values' => $values,
            'captcha' => $captcha,
            'facebook_login_url' => $facebook_login_url ?? null,
        ];

        $view = new \Altum\Views\View('register/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}

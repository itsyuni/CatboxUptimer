<?php

namespace Altum\Controllers;

use Altum\Alerts;
use Altum\Database\Database;
use Altum\Middlewares\Csrf;

class AdminSettings extends Controller {

    public function index() {

        /* Get the latest cronjob details */
        settings()->cron = json_decode(db()->where('`key`', 'cron')->getValue('settings', '`value`'));

        if(!empty($_POST)) {

            /* Main Tab */
            $_POST['title'] = filter_var($_POST['title'], FILTER_SANITIZE_STRING);
            $_POST['default_timezone'] = filter_var($_POST['default_timezone'], FILTER_SANITIZE_STRING);
            $_POST['default_theme_style'] = filter_var($_POST['default_theme_style'], FILTER_SANITIZE_STRING);
            $_POST['email_confirmation'] = (bool) $_POST['email_confirmation'];
            $_POST['register_is_enabled'] = (bool) $_POST['register_is_enabled'];
            $_POST['terms_and_conditions_url'] = filter_var($_POST['terms_and_conditions_url'], FILTER_SANITIZE_STRING);
            $_POST['privacy_policy_url'] = filter_var($_POST['privacy_policy_url'], FILTER_SANITIZE_STRING);

            /* Payment Tab */
            $_POST['payment_is_enabled'] = (bool) $_POST['payment_is_enabled'];
            $_POST['payment_codes_is_enabled'] = (bool) $_POST['payment_codes_is_enabled'];
            $_POST['payment_taxes_and_billing_is_enabled'] = (bool) $_POST['payment_taxes_and_billing_is_enabled'];
            $_POST['payment_type'] = in_array($_POST['payment_type'], ['one_time', 'recurring', 'both']) ? filter_var($_POST['payment_type'], FILTER_SANITIZE_STRING) : 'both';
            $_POST['paypal_is_enabled'] = (bool) $_POST['paypal_is_enabled'];
            $_POST['stripe_is_enabled'] = (bool) $_POST['stripe_is_enabled'];
            $_POST['offline_payment_is_enabled'] = (bool) $_POST['offline_payment_is_enabled'];

            /* Affiliate tab */
            $_POST['affiliate_is_enabled'] = (bool) $_POST['affiliate_is_enabled'];
            $_POST['affiliate_commission_type'] = in_array($_POST['affiliate_commission_type'], ['once', 'forever']) ? filter_var($_POST['affiliate_commission_type'], FILTER_SANITIZE_STRING) : 'once';
            $_POST['affiliate_minimum_withdrawal_amount'] = (float) $_POST['affiliate_minimum_withdrawal_amount'];
            $_POST['affiliate_commission_percentage'] = $_POST['affiliate_commission_percentage'] < 1 || $_POST['affiliate_commission_percentage'] > 99 ? 10 : (int) $_POST['affiliate_commission_percentage'];
            $_POST['affiliate_withdrawal_notes'] = trim(filter_var($_POST['affiliate_withdrawal_notes'], FILTER_SANITIZE_STRING));

            /* Business Tab */
            $_POST['business_invoice_is_enabled'] = (bool) $_POST['business_invoice_is_enabled'];

            /* Status pages Tab */
            $_POST['status_pages_domains_is_enabled'] = (bool) $_POST['status_pages_domains_is_enabled'];
            $_POST['status_pages_additional_domains_is_enabled'] = (bool) $_POST['status_pages_additional_domains_is_enabled'];
            $_POST['status_pages_main_domain_is_enabled'] = (bool) $_POST['status_pages_main_domain_is_enabled'];
            $_POST['status_pages_logo_size_limit'] = $_POST['status_pages_logo_size_limit'] > get_max_upload() || $_POST['status_pages_logo_size_limit'] < 0 ? get_max_upload() : (float) $_POST['status_pages_logo_size_limit'];
            $_POST['status_pages_favicon_size_limit'] = $_POST['status_pages_favicon_size_limit'] > get_max_upload() || $_POST['status_pages_favicon_size_limit'] < 0 ? get_max_upload() : (float) $_POST['status_pages_favicon_size_limit'];

            /* Monitors, heartbeats Tab */
            $_POST['monitors_heartbeats_email_reports_is_enabled'] = in_array($_POST['monitors_heartbeats_email_reports_is_enabled'], [0, 'weekly', 'monthly']) ? $_POST['monitors_heartbeats_email_reports_is_enabled'] : 0;
            $_POST['monitors_heartbeats_monitors_ping_method'] = in_array($_POST['monitors_heartbeats_monitors_ping_method'], ['exec', 'fsockopen']) ? $_POST['monitors_heartbeats_monitors_ping_method'] : 'exec';
            $_POST['monitors_heartbeats_twilio_notifications_is_enabled'] = (bool) $_POST['monitors_heartbeats_twilio_notifications_is_enabled'];
            $_POST['monitors_heartbeats_twilio_sid'] = trim($_POST['monitors_heartbeats_twilio_sid']);
            $_POST['monitors_heartbeats_twilio_token'] = trim($_POST['monitors_heartbeats_twilio_token']);
            $_POST['monitors_heartbeats_twilio_number'] = trim($_POST['monitors_heartbeats_twilio_number']);

            /* Captcha Tab */
            $_POST['captcha_type'] = in_array($_POST['captcha_type'], ['basic', 'recaptcha', 'hcaptcha']) ? $_POST['captcha_type'] : 'basic';
            foreach(['login', 'register', 'lost_password', 'resend_activation'] as $key) {
                $_POST['captcha_' . $key . '_is_enabled'] = (bool) $_POST['captcha_' . $key . '_is_enabled'];
            }

            /* Facebook Tab */
            $_POST['facebook_is_enabled'] = (bool) $_POST['facebook_is_enabled'];

            /* Ads Tab */
            $_POST['ads_header'] = $_POST['a_header'];
            $_POST['ads_footer'] = $_POST['a_footer'];
            $_POST['ads_header_status_pages'] = $_POST['a_header_status_pages'];
            $_POST['ads_footer_status_pages'] = $_POST['a_footer_status_pages'];

            /* Announcements */
            $_POST['announcements_id'] = md5($_POST['announcements_content']);
            $_POST['announcements_text_color'] = !preg_match('/#([A-Fa-f0-9]{3,4}){1,2}\b/i', $_POST['announcements_text_color']) ? '#000' : $_POST['announcements_text_color'];
            $_POST['announcements_background_color'] = !preg_match('/#([A-Fa-f0-9]{3,4}){1,2}\b/i', $_POST['announcements_background_color']) ? '#fff' : $_POST['announcements_background_color'];
            $_POST['announcements_show_logged_in'] = (bool) isset($_POST['announcements_show_logged_in']);
            $_POST['announcements_show_logged_out'] = (bool) isset($_POST['announcements_show_logged_out']);

            /* SMTP Tab */
            $_POST['smtp_auth'] = (bool) isset($_POST['smtp_auth']);
            $_POST['smtp_username'] = filter_var($_POST['smtp_username'] ?? '', FILTER_SANITIZE_STRING);
            $_POST['smtp_password'] = $_POST['smtp_password'] ?? '';

            /* Email notifications */
            $_POST['email_notifications_emails'] = str_replace(' ', '', $_POST['email_notifications_emails']);
            $_POST['email_notifications_new_user'] = (bool) isset($_POST['email_notifications_new_user']);
            $_POST['email_notifications_new_payment'] = (bool) isset($_POST['email_notifications_new_payment']);
            $_POST['email_notifications_new_domain'] = (bool) isset($_POST['email_notifications_new_domain']);

            /* Webhooks */
            $_POST['webhooks_user_new'] = trim(filter_var($_POST['webhooks_user_new'], FILTER_SANITIZE_STRING));
            $_POST['webhooks_user_delete'] = trim(filter_var($_POST['webhooks_user_delete'], FILTER_SANITIZE_STRING));

            /* Offload */
            $_POST['offload_assets_url'] = trim(filter_var($_POST['offload_assets_url'], FILTER_SANITIZE_STRING));

            /* Check for errors & process  potential uploads */
            $image_allowed_extensions = [
                'logo' => ['jpg', 'jpeg', 'png', 'svg', 'gif'],
                'favicon' => ['png', 'ico', 'gif'],
                'opengraph' => ['jpg', 'jpeg', 'png', 'gif'],
            ];
            $image = [
                'logo' => !empty($_FILES['logo']['name']) && !isset($_POST['logo_remove']),
                'favicon' => !empty($_FILES['favicon']['name']) && !isset($_POST['favicon_remove']),
                'opengraph' => !empty($_FILES['opengraph']['name']) && !isset($_POST['opengraph_remove']),
            ];

            foreach(['logo', 'favicon', 'opengraph'] as $image_key) {
                if($image[$image_key]) {
                    $file_name = $_FILES[$image_key]['name'];
                    $file_extension = explode('.', $file_name);
                    $file_extension = mb_strtolower(end($file_extension));
                    $file_temp = $_FILES[$image_key]['tmp_name'];

                    if(!in_array($file_extension, $image_allowed_extensions[$image_key])) {
                        Alerts::add_error(language()->global->error_message->invalid_file_type);
                    }

                    if(!\Altum\Plugin::is_active('offload') || (\Altum\Plugin::is_active('offload') && !settings()->offload->uploads_url)) {
                        if(!is_writable(UPLOADS_PATH . $image_key . '/')) {
                            Alerts::add_error(sprintf(language()->global->error_message->directory_not_writable, UPLOADS_PATH . $image_key . '/'));
                        }
                    }

                    if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                        /* Generate new name for image */
                        $image_new_name = md5(time() . rand()) . '.' . $file_extension;

                        /* Offload uploading */
                        if(\Altum\Plugin::is_active('offload') && settings()->offload->uploads_url) {
                            try {
                                $s3 = new \Aws\S3\S3Client(get_aws_s3_config());

                                /* Delete current image */
                                $s3->deleteObject([
                                    'Bucket' => settings()->offload->storage_name,
                                    'Key' => 'uploads/' . $image_key . '/' . settings()->{$image_key},
                                ]);

                                /* Upload image */
                                $result = $s3->putObject([
                                    'Bucket' => settings()->offload->storage_name,
                                    'Key' => 'uploads/' . $image_key . '/' . $image_new_name,
                                    'ContentType' => mime_content_type($file_temp),
                                    'SourceFile' => $file_temp,
                                    'ACL' => 'public-read'
                                ]);
                            } catch (\Exception $exception) {
                                Alerts::add_error($exception->getMessage());
                            }
                        }

                        /* Local uploading */
                        else {
                            /* Delete current image */
                            if(!empty(settings()->{$image_key}) && file_exists(UPLOADS_PATH . $image_key . '/' . settings()->{$image_key})) {
                                unlink(UPLOADS_PATH . $image_key . '/' . settings()->{$image_key});
                            }

                            /* Upload the original */
                            move_uploaded_file($file_temp, UPLOADS_PATH . $image_key . '/' . $image_new_name);
                        }

                        /* Database query */
                        db()->where('`key`', $image_key)->update('settings', ['value' => $image_new_name]);

                    }
                }

                /* Check for the removal of the already uploaded file */
                if(isset($_POST[$image_key . '_remove'])) {

                    /* Offload deleting */
                    if(\Altum\Plugin::is_active('offload') && settings()->offload->uploads_url) {
                        $s3 = new \Aws\S3\S3Client(get_aws_s3_config());
                        $s3->deleteObject([
                            'Bucket' => settings()->offload->storage_name,
                            'Key' => 'uploads/' . $image_key . '/' . settings()->{$image_key},
                        ]);
                    }

                    /* Local deleting */
                    else {
                        /* Delete current file */
                        if(!empty(settings()->{$image_key}) && file_exists(UPLOADS_PATH . $image_key . '/' . settings()->{$image_key})) {
                            unlink(UPLOADS_PATH . $image_key . '/' . settings()->{$image_key});
                        }
                    }

                    /* Database query */
                    db()->where('`key`', $image_key)->update('settings', ['value' => '']);
                }
            }

            if(!Csrf::check()) {
                Alerts::add_error(language()->global->error_message->invalid_csrf_token);
            }

            /* Changing the license process */
            if(!empty($_POST['license_new_license'])) {
                $altumcode_api = 'https://api2.altumcode.com/validate';

                /* Make sure the license is correct */
                $response = \Unirest\Request::post($altumcode_api, [], [
                    'type'              => 'license-update',
                    'license_key'       => $_POST['license_new_license'],
                    'installation_url'  => url(),
                    'product_key'       => PRODUCT_KEY,
                    'product_name'      => PRODUCT_NAME,
                    'product_version'   => PRODUCT_VERSION,
                    'server_ip'         => $_SERVER['SERVER_ADDR'],
                    'client_ip'         => get_ip()
                ]);

                if($response->body->status == 'error') {
                    Alerts::add_error($response->body->message);
                }

                /* Success check */
                if($response->body->status == 'success') {

                    /* Run external SQL if needed */
                    if(!empty($response->body->sql)) {
                        $dump = explode('-- SEPARATOR --', $response->body->sql);

                        foreach($dump as $query) {
                            database()->query($query);
                        }
                    }

                    Alerts::add_success($response->body->message);

                    /* Clear the cache */
                    \Altum\Cache::$adapter->deleteItem('settings');

                    /* Refresh the website settings */
                    $settings = (new \Altum\Models\Settings())->get();

                    if(!in_array($settings->license->type, ['Extended License', 'extended'])) {
                        $_POST['payment_is_enabled'] = false;
                        $_POST['payment_codes_is_enabled'] = false;
                        $_POST['payment_taxes_and_billing_is_enabled'] = false;
                        $_POST['affiliate_is_enabled'] = false;
                    }
                }
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                $settings_keys = [

                    /* Main */
                    'title',
                    'default_language',
                    'default_theme_style',
                    'default_timezone',
                    'email_confirmation',
                    'register_is_enabled',
                    'index_url',
                    'terms_and_conditions_url',
                    'privacy_policy_url',

                    /* Payment */
                    'payment' => [
                        'is_enabled',
                        'type',
                        'brand_name',
                        'currency',
                        'codes_is_enabled',
                        'taxes_and_billing_is_enabled'
                    ],

                    'paypal' => [
                        'is_enabled',
                        'mode',
                        'client_id',
                        'secret'
                    ],

                    'stripe' => [
                        'is_enabled',
                        'publishable_key',
                        'secret_key',
                        'webhook_secret'
                    ],

                    'offline_payment' => [
                        'is_enabled',
                        'instructions'
                    ],

                    /* Affiliate */
                    'affiliate' => [
                        'is_enabled',
                        'commission_type',
                        'minimum_withdrawal_amount',
                        'commission_percentage',
                        'withdrawal_notes',
                    ],

                    /* Business */
                    'business' => [
                        'invoice_is_enabled',
                        'invoice_nr_prefix',
                        'name',
                        'address',
                        'city',
                        'county',
                        'zip',
                        'country',
                        'email',
                        'phone',
                        'tax_type',
                        'tax_id',
                        'custom_key_one',
                        'custom_value_one',
                        'custom_key_two',
                        'custom_value_two'
                    ],

                    /* Status pages */
                    'status_pages' => [
                        'domains_is_enabled',
                        'additional_domains_is_enabled',
                        'main_domain_is_enabled',
                        'logo_size_limit',
                        'favicon_size_limit',
                    ],

                    /* Monitors, Heartbeats */
                    'monitors_heartbeats' => [
                        'email_reports_is_enabled',
                        'monitors_ping_method',
                        'twilio_notifications_is_enabled',
                        'twilio_sid',
                        'twilio_token',
                        'twilio_number',
                    ],

                    /* Captcha */
                    'captcha' => [
                        'type',
                        'recaptcha_public_key',
                        'recaptcha_private_key',
                        'hcaptcha_site_key',
                        'hcaptcha_secret_key',
                        'login_is_enabled',
                        'register_is_enabled',
                        'lost_password_is_enabled',
                        'resend_activation_is_enabled'
                    ],

                    /* Facebook */
                    'facebook' => [
                        'is_enabled',
                        'app_id',
                        'app_secret'
                    ],

                    /* Ads */
                    'ads' => [
                        'header',
                        'footer',
                        'header_status_pages',
                        'footer_status_pages'
                    ],

                    /* Socials */
                    'socials' => array_keys(require APP_PATH . 'includes/admin_socials.php'),

                    /* SMTP */
                    'smtp' => [
                        'from_name',
                        'from',
                        'host',
                        'encryption',
                        'port',
                        'auth',
                        'username',
                        'password'
                    ],

                    /* Custom */
                    'custom' => [
                        'head_js',
                        'head_css'
                    ],

                    /* Announcements */
                    'announcements' => [
                        'id',
                        'content',
                        'text_color',
                        'background_color',
                        'show_logged_in',
                        'show_logged_out'
                    ],

                    /* Email Notifications */
                    'email_notifications' => [
                        'emails',
                        'new_user',
                        'new_payment',
                        'new_domain'
                    ],

                    /* Webhooks */
                    'webhooks' => [
                        'user_new',
                        'user_delete'
                    ],
                ];

                /* Offload plugin */
                if(\Altum\Plugin::is_active('offload')) {
                    $settings_keys['offload'] = [
                        'assets_url',
                        'provider',
                        'endpoint_url',
                        'uploads_url',
                        'access_key',
                        'secret_access_key',
                        'storage_name',
                        'region',
                    ];
                }

                /* Go over each key and make sure to update it accordingly */
                foreach($settings_keys as $key => $value) {

                    /* Should we update the value? */
                    $to_update = true;

                    if(is_array($value)) {

                        $values_array = [];

                        foreach($value as $sub_key) {

                            /* Check if the field needs cleaning */
                            if(!in_array($key . '_' . $sub_key, ['announcements_content', 'custom_head_css', 'custom_head_js', 'ads_header', 'ads_footer', 'ads_header_status_pages', 'ads_footer_status_pages', 'offline_payment_instructions'])) {
                                $values_array[$sub_key] = Database::clean_string($_POST[$key . '_' . $sub_key]);
                            } else {
                                $values_array[$sub_key] = $_POST[$key . '_' . $sub_key];
                            }
                        }

                        $value = json_encode($values_array);

                        /* Check if new value is the same with the old one */
                        if(json_encode(settings()->{$key}) == $value) {
                            $to_update = false;
                        }

                    } else {
                        $key = $value;
                        $value = $_POST[$key];

                        /* Check if new value is the same with the old one */
                        if(settings()->{$key} == $value) {
                            $to_update = false;
                        }
                    }

                    if($to_update) {
                        db()->where('`key`', $key)->update('settings', ['value' => $value]);
                    }

                }

                /* Clear the cache */
                \Altum\Cache::$adapter->deleteItem('settings');

                /* Set a nice success message */
                Alerts::add_success(language()->admin_settings->success_message->saved);

                /* Refresh the page */
                redirect('admin/settings');

            }
        }

        /* Main View */
        $view = new \Altum\Views\View('admin/settings/index', (array) $this);

        $this->add_view_content('content', $view->run());

    }

    public function testemail() {

        if(!Csrf::check()) {
            redirect('admin/settings');
        }

        $result = send_mail(settings()->smtp->from, settings()->title . ' - Test Email', 'This is just a test email to confirm the smtp email settings!', true);

        if($result->ErrorInfo == '') {
            Alerts::add_success(language()->admin_settings->success_message->email);
        } else {
            Alerts::add_error(sprintf(language()->admin_settings->error_message->email, $result->ErrorInfo));
            Alerts::add_info(implode('<br />', $result->errors));
        }

        redirect('admin/settings');
    }
}

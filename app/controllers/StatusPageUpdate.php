<?php

namespace Altum\Controllers;

use Altum\Alerts;
use Altum\Database\Database;
use Altum\Middlewares\Authentication;
use Altum\Middlewares\Csrf;
use Altum\Title;

class StatusPageUpdate extends Controller {

    public function index() {

        Authentication::guard();

        $status_page_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        if(!$status_page = db()->where('status_page_id', $status_page_id)->where('user_id', $this->user->user_id)->getOne('status_pages')) {
            redirect('status-pages');
        }

        /* Genereate the status_page full URL base */
        $status_page->full_url = (new \Altum\Models\StatusPage())->get_status_page_full_url($status_page, $this->user);

        $status_page->socials = json_decode($status_page->socials);
        $status_page->monitors_ids = json_decode($status_page->monitors_ids);

        /* Get available custom domains */
        $domains = (new \Altum\Models\Domain())->get_available_domains_by_user($this->user, true, $status_page->status_page_id);

        /* Get available projects servers */
        $projects = (new \Altum\Models\Projects())->get_projects_by_user_id($this->user->user_id);

        /* Get all the available monitors */
        $monitors = (new \Altum\Models\Monitors())->get_monitors_by_user_id($this->user->user_id);

        if(!empty($_POST)) {
            $_POST['url'] = !empty($_POST['url']) && $this->user->plan_settings->custom_url_is_enabled ? get_slug(Database::clean_string($_POST['url'])) : false;
            $_POST['name'] = trim(Database::clean_string($_POST['name']));
            $_POST['description'] = trim(Database::clean_string($_POST['description']));
            $_POST['domain_id'] = isset($_POST['domain_id']) && isset($domains[$_POST['domain_id']]) ? (!empty($_POST['domain_id']) ? (int) $_POST['domain_id'] : null) : null;
            $_POST['is_main_status_page'] = (bool) isset($_POST['is_main_status_page']) && isset($domains[$_POST['domain_id']]) && $domains[$_POST['domain_id']]->type == 0;
            $_POST['monitors_ids'] = empty($_POST['monitors_ids']) ? [] : array_map(
                function($monitor_id) {
                    return (int) $monitor_id;
                },
                array_filter($_POST['monitors_ids'], function($monitor_id) use($monitors) {
                    return array_key_exists($monitor_id, $monitors);
                })
            );

            $_POST['project_id'] = !empty($_POST['project_id']) && array_key_exists($_POST['project_id'], $projects) ? (int) $_POST['project_id'] : null;
            $_POST['timezone']  = in_array($_POST['timezone'], \DateTimeZone::listIdentifiers()) ? Database::clean_string($_POST['timezone']) : settings()->default_timezone;
            $_POST['password'] = !empty($_POST['password']) ?
                ($_POST['password'] != $status_page->password ? password_hash($_POST['password'], PASSWORD_DEFAULT) : $status_page->password)
                : null;
            $_POST['is_se_visible'] = (int) (bool) isset($_POST['is_se_visible']);
            $_POST['is_removed_branding'] = (int) (bool) isset($_POST['is_removed_branding']);

            $_POST['custom_css'] = trim(filter_var($_POST['custom_css'], FILTER_SANITIZE_STRING));
            $_POST['custom_js'] = trim($_POST['custom_js']);

            /* Make sure the socials sent are proper */
            $socials = require APP_PATH . 'includes/s/socials.php';

            foreach($_POST['socials'] as $key => $value) {

                if(!array_key_exists($key, $socials)) {
                    unset($_POST['socials'][$key]);
                } else {
                    $_POST['socials'][$key] = Database::clean_string($_POST['socials'][$key]);
                }

            }

            /* Check for any errors */
            $required_fields = ['name'];
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]))) {
                    Alerts::add_field_error($field, language()->global->error_message->empty_field);
                }
            }

            if(!Csrf::check()) {
                Alerts::add_error(language()->global->error_message->invalid_csrf_token);
            }

            /* Check for duplicate url if needed */
            if(
                ($_POST['url'] && $this->user->plan_settings->custom_url_is_enabled && $_POST['url'] != $status_page->url)
                || ($status_page->domain_id != $_POST['domain_id'])
            ) {

                $domain_id_where = $_POST['domain_id'] ? "AND `domain_id` = {$_POST['domain_id']}" : "AND `domain_id` IS NULL";
                $is_existing_status_page = database()->query("SELECT `status_page_id` FROM `status_pages` WHERE `url` = '{$_POST['url']}' {$domain_id_where}")->num_rows;

                if($is_existing_status_page) {
                    Alerts::add_field_error('url', language()->status_page->error_message->url_exists);
                }

            }

            /* Image uploads */
            $logo_allowed_extensions = ['jpg', 'jpeg', 'png', 'svg', 'gif'];
            $favicon_allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'ico'];

            $logo = !empty($_FILES['logo']['name']) && !isset($_POST['logo_remove']);
            $favicon = !empty($_FILES['favicon']['name']) && !isset($_POST['favicon_remove']);

            /* Check for any errors on the logo image */
            if($logo) {
                $logo_file_name = $_FILES['logo']['name'];
                $logo_file_extension = explode('.', $logo_file_name);
                $logo_file_extension = mb_strtolower(end($logo_file_extension));
                $logo_file_temp = $_FILES['logo']['tmp_name'];

                if(!in_array($logo_file_extension, $logo_allowed_extensions)) {
                    Alerts::add_field_error('logo', language()->global->error_message->invalid_file_type);
                }

                if(!settings()->offload->uploads_url) {
                    if(!is_writable(UPLOADS_PATH . 'status_pages_logos/')) {
                        Alerts::add_field_error('logo', sprintf(language()->global->error_message->directory_not_writable, UPLOADS_PATH . 'status_pages_logos/'));
                    }
                }

                if($_FILES['logo']['size'] > settings()->status_pages->logo_size_limit * 1000000) {
                    Alerts::add_field_error('logo', sprintf(language()->global->error_message->file_size_limit, settings()->status_pages->logo_size_limit));
                }

                if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                    /* Generate new name for logo */
                    $logo_new_name = md5(time() . rand()) . '.' . $logo_file_extension;

                    /* Offload uploading */
                    if(\Altum\Plugin::is_active('offload') && settings()->offload->uploads_url) {
                        try {
                            $s3 = new \Aws\S3\S3Client(get_aws_s3_config());

                            /* Delete current image */
                            $s3->deleteObject([
                                'Bucket' => settings()->offload->storage_name,
                                'Key' => 'uploads/status_pages_logos/' . $status_page->logo,
                            ]);

                            /* Upload image */
                            $result = $s3->putObject([
                                'Bucket' => settings()->offload->storage_name,
                                'Key' => 'uploads/status_pages_logos/' . $logo_new_name,
                                'ContentType' => mime_content_type($logo_file_temp),
                                'SourceFile' => $logo_file_temp,
                                'ACL' => 'public-read'
                            ]);
                        } catch (\Exception $exception) {
                            Alerts::add_error($exception->getMessage());
                        }
                    }

                    /* Local uploading */
                    else {
                        /* Delete current file */
                        if(!empty($status_page->logo) && file_exists(UPLOADS_PATH . 'status_pages_logos/' . $status_page->logo)) {
                            unlink(UPLOADS_PATH . 'status_pages_logos/' . $status_page->logo);
                        }

                        /* Upload the original */
                        move_uploaded_file($logo_file_temp, UPLOADS_PATH . 'status_pages_logos/' . $logo_new_name);
                    }

                    /* Database query */
                    db()->where('status_page_id', $status_page->status_page_id)->update('status_pages', ['logo' => $logo_new_name]);

                }
            }

            /* Check for the removal of the already uploaded file */
            if(isset($_POST['logo_remove'])) {
                /* Offload deleting */
                if(\Altum\Plugin::is_active('offload') && settings()->offload->uploads_url) {
                    $s3 = new \Aws\S3\S3Client(get_aws_s3_config());
                    $s3->deleteObject([
                        'Bucket' => settings()->offload->storage_name,
                        'Key' => 'uploads/status_pages_logos/' . $status_page->logo,
                    ]);
                }

                /* Local deleting */
                else {
                    /* Delete current file */
                    if(!empty($status_page->logo) && file_exists(UPLOADS_PATH . 'status_pages_logos/' . $status_page->logo)) {
                        unlink(UPLOADS_PATH . 'status_pages_logos/' . $status_page->logo);
                    }
                }

                /* Database query */
                db()->where('status_page_id', $status_page->status_page_id)->update('status_pages', ['logo' => null]);
            }

            /* Check for any errors on the favicon image */
            if($favicon) {
                $favicon_file_name = $_FILES['favicon']['name'];
                $favicon_file_extension = explode('.', $favicon_file_name);
                $favicon_file_extension = mb_strtolower(end($favicon_file_extension));
                $favicon_file_temp = $_FILES['favicon']['tmp_name'];

                if(!in_array($favicon_file_extension, $favicon_allowed_extensions)) {
                    Alerts::add_field_error('favicon', language()->global->error_message->invalid_file_type);
                }

                if(!settings()->offload->uploads_url) {
                    if(!is_writable(UPLOADS_PATH . 'status_pages_favicons/')) {
                        Alerts::add_field_error('favicon', sprintf(language()->global->error_message->directory_not_writable, UPLOADS_PATH . 'status_pages_favicons/'));
                    }
                }

                if($_FILES['favicon']['size'] > settings()->status_pages->favicon_size_limit * 1000000) {
                    Alerts::add_field_error('favicon', sprintf(language()->global->error_message->file_size_limit, settings()->status_pages->favicon_size_limit));
                }

                if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                    /* Generate new name for logo */
                    $favicon_new_name = md5(time() . rand()) . '.' . $favicon_file_extension;

                    /* Offload uploading */
                    if(\Altum\Plugin::is_active('offload') && settings()->offload->uploads_url) {
                        try {
                            $s3 = new \Aws\S3\S3Client(get_aws_s3_config());

                            /* Delete current image */
                            $s3->deleteObject([
                                'Bucket' => settings()->offload->storage_name,
                                'Key' => 'uploads/status_pages_favicons/' . $status_page->favicon,
                            ]);

                            /* Upload image */
                            $result = $s3->putObject([
                                'Bucket' => settings()->offload->storage_name,
                                'Key' => 'uploads/status_pages_favicons/' . $favicon_new_name,
                                'ContentType' => mime_content_type($favicon_file_temp),
                                'SourceFile' => $favicon_file_temp,
                                'ACL' => 'public-read'
                            ]);
                        } catch (\Exception $exception) {
                            die($exception->getMessage());
                        }
                    }

                    /* Local uploading */
                    else {
                        /* Delete current file */
                        if(!empty($status_page->favicon) && file_exists(UPLOADS_PATH . 'status_pages_favicons/' . $status_page->favicon)) {
                            unlink(UPLOADS_PATH . 'status_pages_favicons/' . $status_page->favicon);
                        }

                        /* Upload the original */
                        move_uploaded_file($favicon_file_temp, UPLOADS_PATH . 'status_pages_favicons/' . $favicon_new_name);
                    }

                    /* Database query */
                    db()->where('status_page_id', $status_page->status_page_id)->update('status_pages', ['favicon' => $favicon_new_name]);

                }
            }

            /* Check for the removal of the already uploaded file */
            if(isset($_POST['favicon_remove'])) {
                /* Offload deleting */
                if(\Altum\Plugin::is_active('offload') && settings()->offload->uploads_url) {
                    $s3 = new \Aws\S3\S3Client(get_aws_s3_config());
                    $s3->deleteObject([
                        'Bucket' => settings()->offload->storage_name,
                        'Key' => 'uploads/status_pages_favicons/' . $status_page->favicon,
                    ]);
                }

                /* Local deleting */
                else {
                    /* Delete current file */
                    if(!empty($status_page->favicon) && file_exists(UPLOADS_PATH . 'status_pages_favicons/' . $status_page->favicon)) {
                        unlink(UPLOADS_PATH . 'status_pages_favicons/' . $status_page->favicon);
                    }
                }

                /* Database query */
                db()->where('status_page_id', $status_page->status_page_id)->update('status_pages', ['favicon' => null]);
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {
                $theme = 'new-york';
                $monitors_ids = json_encode($_POST['monitors_ids']);
                $socials = json_encode($_POST['socials']);

                if(!$_POST['url']) {
                    $is_existing_status_page = true;

                    /* Generate random url if not specified */
                    while($is_existing_status_page) {
                        $_POST['url'] = mb_strtolower(string_generate(10));

                        $domain_id_where = $_POST['domain_id'] ? "AND `domain_id` = {$_POST['domain_id']}" : "AND `domain_id` IS NULL";
                        $is_existing_status_page = database()->query("SELECT `status_page_id` FROM `status_pages` WHERE `url` = '{$_POST['url']}' {$domain_id_where}")->num_rows;
                    }

                }

                /* Prepare the statement and execute query */
                db()->where('status_page_id', $status_page->status_page_id)->update('status_pages', [
                    'domain_id' => $_POST['domain_id'],
                    'monitors_ids' => $monitors_ids,
                    'url' => $_POST['url'],
                    'name' => $_POST['name'],
                    'description' => $_POST['description'],
                    'project_id' => $_POST['project_id'],
                    'timezone' => $_POST['timezone'],
                    'password' => $_POST['password'],
                    'is_se_visible' => $_POST['is_se_visible'],
                    'is_removed_branding' => $_POST['is_removed_branding'],
                    'socials' => $socials,
                    'custom_css' => $_POST['custom_css'],
                    'custom_js' => $_POST['custom_js'],
                    'theme' => $theme,
                    'last_datetime' => \Altum\Date::$date,
                ]);

                /* Update custom domain if needed */
                if($_POST['is_main_status_page']) {

                    /* If the main status page of a particular domain is changing, update the old domain as well to "free" it */
                    if($_POST['domain_id'] != $status_page->domain_id) {
                        /* Database query */
                        db()->where('domain_id', $status_page->domain_id)->update('domains', [
                            'status_page_id' => null,
                            'last_datetime' => \Altum\Date::$date,
                        ]);
                    }

                    /* Database query */
                    db()->where('domain_id', $_POST['domain_id'])->update('domains', [
                        'status_page_id' => $status_page_id,
                        'last_datetime' => \Altum\Date::$date,
                    ]);

                }

                /* Update old main custom domain if needed */
                if(!$_POST['is_main_status_page'] && $status_page->domain_id && $domains[$status_page->domain_id]->status_page_id == $status_page->status_page_id) {
                    /* Database query */
                    db()->where('domain_id', $status_page->domain_id)->update('domains', [
                        'status_page_id' => null,
                        'last_datetime' => \Altum\Date::$date,
                    ]);
                }

                /* Clear the cache */
                \Altum\Cache::$adapter->deleteItemsByTag('status_page_id=' . $status_page_id);
                \Altum\Cache::$adapter->deleteItemsByTag('user_id=' . $this->user->user_id);

                /* Set a nice success message */
                Alerts::add_success(language()->status_page_update->success_message);

                redirect('status-page-update/' . $status_page->status_page_id);
            }

        }

        /* Delete Modal */
        $view = new \Altum\Views\View('status-page/status_page_delete_modal', (array) $this);
        \Altum\Event::add_content($view->run(), 'modals');

        /* Set a custom title */
        Title::set(sprintf(language()->status_page_update->title, $status_page->name));

        /* Prepare the View */
        $data = [
            'monitors' => $monitors,
            'domains' => $domains,
            'projects' => $projects,
            'status_page' => $status_page
        ];

        $view = new \Altum\Views\View('status-page-update/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}

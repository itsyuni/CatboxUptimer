<?php

namespace Altum\Controllers;

use Altum\Date;

class Cron extends Controller {

    public function index() {
        die();
    }

    private function initiate() {
        /* Initiation */
        set_time_limit(0);

        /* Make sure the key is correct */
        if(!isset($_GET['key']) || (isset($_GET['key']) && $_GET['key'] != settings()->cron->key)) {
            die();
        }
    }

    public function reset() {

        $this->initiate();

        $date = \Altum\Date::$date;

        /* Update cron job last run date */
        $new_cron = json_encode(array_merge((array) settings()->cron, ['reset_datetime' => $date]));
        db()->where('`key`', 'cron')->update('settings', ['value' => $new_cron]);

        /* Delete old users logs */
        $ninety_days_ago_datetime = (new \DateTime())->modify('-90 days')->format('Y-m-d H:i:s');
        database()->query("DELETE FROM `users_logs` WHERE `date` < '{$ninety_days_ago_datetime}'");

        /* Make sure the reset date month is different than the current one to avoid double resetting */
        $reset_date = (new \DateTime(settings()->cron->reset_date))->format('m');
        $current_date = (new \DateTime())->format('m');

        if($reset_date != $current_date) {

            /* Update the settings with the updated time */
            $new_cron = json_encode(array_merge((array) settings()->cron, ['reset_date' => $date]));

            /* Database query */
            db()->where('`key`', 'cron')->update('settings', ['value' => $new_cron]);

            /* Clear the cache */
            \Altum\Cache::$adapter->deleteItem('settings');
        }
    }

    public function monitors() {

        $this->initiate();

        $date = \Altum\Date::$date;

        /* Update cron job last run date */
        $new_cron = json_encode(array_merge((array) settings()->cron, ['monitors_datetime' => $date]));
        db()->where('`key`', 'cron')->update('settings', ['value' => $new_cron]);

        /* Get potential monitors from users that have almost all the conditions to get an email report right now */
        $result = database()->query("
            SELECT
                `monitors`.*,
                   
                `users`.`email`,
                `users`.`plan_settings`,
                `users`.`language`,
                `users`.`timezone`
            FROM 
                `monitors`
            LEFT JOIN 
                `users` ON `monitors`.`user_id` = `users`.`user_id` 
            WHERE 
                `monitors`.`is_enabled` = 1
                AND `monitors`.`next_check_datetime` <= '{$date}' 
                AND `users`.`active` = 1
            LIMIT 50
        ");

        /* Get available ping servers */
        $ping_servers = (new \Altum\Models\PingServers())->get_ping_servers();

        /* Go through each result */
        while($row = $result->fetch_object()) {
            $row->plan_settings = json_decode($row->plan_settings);
            $row->settings = json_decode($row->settings);
            $row->ping_servers_ids = json_decode($row->ping_servers_ids);
            $row->notifications = json_decode($row->notifications);

            $check = \Altum\Monitor::check($row, $ping_servers);

            /* If the monitor is down, double check to be sure */
            if(!$check['is_ok']) {
                sleep(3);
                $check = \Altum\Monitor::check($row, $ping_servers);
            }

            $vars = \Altum\Monitor::vars($row, $check);

            /* Check for an SSL certificate if needed */
            if($row->type == 'website' && $row->settings->ssl_is_enabled) {
                $row->ssl = json_decode($row->ssl);

                /* Only check for a certificate once in a while */
                $ssl_last_check_datetime_diff = isset($row->ssl->last_check_datetime) ? (new \DateTime($row->ssl->last_check_datetime))->diff((new \DateTime())) : null;

                if(!$ssl_last_check_datetime_diff || $ssl_last_check_datetime_diff->days > 10) {
                    $certificate = get_website_certificate($row->target);

                    /* Create the new SSL object */
                    $row->ssl = [
                        'last_check_datetime' => Date::$date,
                        'last_notification_datetime' => $row->ssl->last_notification_datetime ?? null
                    ];
                    if($certificate) {
                        $row->ssl = array_merge($row->ssl, [
                            'issuer_name' => $certificate['issuer']['O'],
                            'issuer_full' => $certificate['issuer']['CN'],
                            'start_date' => (new \DateTime())->setTimestamp($certificate['validFrom_time_t'])->format('Y-m-d H:i:s'),
                            'end_date' => (new \DateTime())->setTimestamp($certificate['validTo_time_t'])->format('Y-m-d H:i:s'),
                        ]);
                    }

                    $row->ssl = (object) $row->ssl;
                }

                /* Check if we should send out an alert email about the expiration */
                if($ssl_last_check_datetime_diff && $row->notifications->ssl_email_is_enabled) {
                    /* Trigger email notification if needed */
                    $ssl_expires_in_days = (new \DateTime($row->ssl->end_date))->diff(new \DateTime())->days;

                    /* Make sure the user was not notified already */
                    if($ssl_expires_in_days <= $row->notifications->ssl_email_is_enabled && (!$row->ssl->last_notification_datetime || ($row->ssl->last_notification_datetime && (new \DateTime($row->ssl->last_notification_datetime))->diff(new \DateTime())->days > 30))) {

                        /* Get the language for the user and set the timezone */
                        $language = language($row->language);
                        \Altum\Date::$timezone = $row->timezone;

                        /* Prepare the email title */
                        $email_title = sprintf($language->cron->monitor_ssl_expiration_notice->title, $row->name, $ssl_expires_in_days);

                        /* Prepare the View for the email content */
                        $data = [
                            'language' => $language,
                            'ssl_expires_in_days' => $ssl_expires_in_days,
                            'row' => $row
                        ];

                        $email_content = (new \Altum\Views\View('partials/cron/monitor_ssl_expiration_notice', (array)$this))->run($data);

                        /* Send the email */
                        send_mail($row->email, $email_title, $email_content);

                        $row->ssl->last_notification_datetime = Date::$date;

                    }
                }

                $row->ssl = json_encode($row->ssl);
            }


            /* Insert the history log */
            $monitor_log_id = db()->insert('monitors_logs', [
                'monitor_id' => $row->monitor_id,
                'ping_server_id' => $check['ping_server_id'],
                'user_id' => $row->user_id,
                'is_ok' => $check['is_ok'],
                'response_time' => $check['response_time'],
                'response_status_code' => $check['response_status_code'],
                'error' => isset($check['error']) ? json_encode($check['error']) : null,
                'datetime' => \Altum\Date::$date
            ]);

            /* Create / update an incident if needed */
            $incident_id = $row->incident_id;

            if(!$check['is_ok'] && !$row->incident_id) {

                /* Database query */
                $incident_id = db()->insert('incidents', [
                    'monitor_id' => $row->monitor_id,
                    'start_monitor_log_id' => $monitor_log_id,
                    'start_datetime' => \Altum\Date::$date
                ]);

                if($row->plan_settings->email_notifications_is_enabled && $row->notifications->email_is_enabled) {
                    /* Get the language for the user and set the timezone */
                    $language = language($row->language);
                    \Altum\Date::$timezone = $row->timezone;

                    /* Prepare the email title */
                    $email_title = sprintf($language->cron->is_not_ok->title, $row->name);

                    /* Prepare the View for the email content */
                    $data = [
                        'language' => $language,
                        'row' => $row
                    ];

                    $email_content = (new \Altum\Views\View('partials/cron/monitor_is_not_ok', (array)$this))->run($data);

                    /* Send the email */
                    send_mail($row->email, $email_title, $email_content);
                }

                /* Webhook notification */
                if($row->notifications->webhook) {
                    try {
                        \Unirest\Request::post($row->notifications->webhook, [], [
                            'monitor_id' => $row->monitor_id,
                            'name' => $row->name,
                            'is_ok' => $check['is_ok'],
                        ]);
                    } catch (\Exception $exception) {
                        // :)
                    }
                }

                /* Slack notification */
                if($row->notifications->slack) {
                    try {
                        \Unirest\Request::post(
                            $row->notifications->slack,
                            ['Accept' => 'application/json'],
                            \Unirest\Request\Body::json([
                                'text' => sprintf(language()->monitor->slack_notifications->is_not_ok, $row->name),
                                'username' => settings()->title,
                                'icon_emoji' => ':large_red_square:'
                            ])
                        );
                    } catch (\Exception $exception) {
                        // :)
                    }
                }

                /* Twilio Sms Notification */
                if(settings()->monitors_heartbeats->twilio_notifications_is_enabled && $row->plan_settings->twilio_notifications_is_enabled && $row->notifications->twilio) {
                    try {
                        \Unirest\Request::auth(settings()->monitors_heartbeats->twilio_sid, settings()->monitors_heartbeats->twilio_token);

                        \Unirest\Request::post(
                            sprintf('https://api.twilio.com/2010-04-01/Accounts/%s/Messages.json', settings()->monitors_heartbeats->twilio_sid),
                            [],
                            [
                                'From' => settings()->monitors_heartbeats->twilio_number,
                                'To' => $row->notifications->twilio,
                                'Body' => sprintf(language()->monitor->twilio_notifications->is_not_ok, $row->name),
                            ]
                        );
                    } catch (\Exception $exception) {
                        // :)
                    }

                    \Unirest\Request::auth('', '');
                }
            }

            /* Close incident */
            if($check['is_ok'] && $row->incident_id) {

                /* Database query */
                db()->where('incident_id', $row->incident_id)->update('incidents', [
                    'monitor_id' => $row->monitor_id,
                    'end_monitor_log_id' => $monitor_log_id,
                    'end_datetime' => \Altum\Date::$date
                ]);

                $incident_id = null;

                /* Get details about the incident */
                $monitor_incident = db()->where('incident_id', $row->incident_id)->getOne('incidents', ['start_datetime', 'end_datetime']);

                if($row->plan_settings->email_notifications_is_enabled && $row->notifications->email_is_enabled) {
                    /* Get the language for the user */
                    $language = language($row->language);
                    \Altum\Date::$timezone = $row->timezone;

                    /* Prepare the email title */
                    $email_title = sprintf($language->cron->is_ok->title, $row->name);

                    /* Prepare the View for the email content */
                    $data = [
                        'language' => $language,
                        'monitor_incident' => $monitor_incident,
                        'row' => $row
                    ];

                    $email_content = (new \Altum\Views\View('partials/cron/monitor_is_ok', (array)$this))->run($data);

                    /* Send the email */
                    send_mail($row->email, $email_title, $email_content);
                }

                /* Webhook notification */
                if($row->notifications->webhook) {
                    try {
                        \Unirest\Request::post($row->notifications->webhook, [], [
                            'monitor_id' => $row->monitor_id,
                            'name' => $row->name,
                            'is_ok' => $check['is_ok'],
                        ]);
                    } catch (\Exception $exception) {
                        // :)
                    }
                }

                /* Slack notification */
                if($row->notifications->slack) {
                    try {
                        \Unirest\Request::post(
                            $row->notifications->slack,
                            ['Accept' => 'application/json'],
                            \Unirest\Request\Body::json([
                                'text' => sprintf(language()->monitor->slack_notifications->is_ok, $row->name),
                                'username' => settings()->title,
                                'icon_emoji' => ':large_green_circle:'
                            ])
                        );
                    } catch (\Exception $exception) {
                        // :)
                    }
                }

                /* Twilio Sms Notification */
                if(settings()->monitors_heartbeats->twilio_notifications_is_enabled && $row->plan_settings->twilio_notifications_is_enabled && $row->notifications->twilio) {
                    try {
                        \Unirest\Request::auth(settings()->monitors_heartbeats->twilio_sid, settings()->monitors_heartbeats->twilio_token);

                        \Unirest\Request::post(
                            sprintf('https://api.twilio.com/2010-04-01/Accounts/%s/Messages.json', settings()->monitors_heartbeats->twilio_sid),
                            [],
                            [
                                'From' => settings()->monitors_heartbeats->twilio_number,
                                'To' => $row->notifications->twilio,
                                'Body' => sprintf(language()->monitor->twilio_notifications->is_ok, $row->name),
                            ]
                        );
                    } catch (\Exception $exception) {
                        // :)
                    }

                    \Unirest\Request::auth('', '');
                }
            }

            /* Update the monitor */
            db()->where('monitor_id', $row->monitor_id)->update('monitors', [
                'incident_id' => $incident_id,
                'ssl' => $row->ssl,
                'is_ok' => $check['is_ok'],
                'uptime' => $vars['uptime'],
                'uptime_seconds' => $vars['uptime_seconds'],
                'downtime' => $vars['downtime'],
                'downtime_seconds' => $vars['downtime_seconds'],
                'average_response_time' => $vars['average_response_time'],
                'total_checks' => db()->inc(),
                'total_ok_checks' => $vars['total_ok_checks'],
                'total_not_ok_checks' => $vars['total_not_ok_checks'],
                'last_check_datetime' => $vars['last_check_datetime'],
                'next_check_datetime' => $vars['next_check_datetime'],
                'main_ok_datetime' => $vars['main_ok_datetime'],
                'last_ok_datetime' => $vars['last_ok_datetime'],
                'main_not_ok_datetime' => $vars['main_not_ok_datetime'],
                'last_not_ok_datetime' => $vars['last_not_ok_datetime'],
            ]);

            /* Clear out old monitor logs */
            $x_days_ago_datetime = (new \DateTime())->modify('-' . ($row->plan_settings->logs_retention ?? 90) . ' days')->format('Y-m-d H:i:s');
            database()->query("DELETE FROM `monitors_logs` WHERE `datetime` < '{$x_days_ago_datetime}'");

            /* Clear the cache */
            \Altum\Cache::$adapter->deleteItemsByTag('monitor_id=' . $row->monitor_id);

        }

    }

    public function heartbeats() {

        $this->initiate();

        $date = \Altum\Date::$date;

        /* Update cron job last run date */
        $new_cron = json_encode(array_merge((array) settings()->cron, ['heartbeats_datetime' => $date]));
        db()->where('`key`', 'cron')->update('settings', ['value' => $new_cron]);

        /* Get potential heartbeats from users that have almost all the conditions to get an email report right now */
        $result = database()->query("
            SELECT
                `heartbeats`.*,
                   
                `users`.`email`,
                `users`.`plan_settings`,
                `users`.`language`,
                `users`.`timezone`
            FROM 
                `heartbeats`
            LEFT JOIN 
                `users` ON `heartbeats`.`user_id` = `users`.`user_id` 
            WHERE 
                `heartbeats`.`is_enabled` = 1
                AND `heartbeats`.`next_run_datetime` <= '{$date}' 
                AND `users`.`active` = 1
            LIMIT 50
        ");

        /* Go through each result */
        while($row = $result->fetch_object()) {
            $row->plan_settings = json_decode($row->plan_settings);
            $row->settings = json_decode($row->settings);
            $row->notifications = json_decode($row->notifications);

            /* Since the result is here, the cron is not working */
            $is_ok = 0;

            /* Insert the history log */
            $heartbeat_log_id = db()->insert('heartbeats_logs', [
                'heartbeat_id' => $row->heartbeat_id,
                'user_id' => $row->user_id,
                'is_ok' => $is_ok,
                'datetime' => \Altum\Date::$date,
            ]);

            /* Assuming, based on the run interval */
            $downtime_seconds_to_add = 0;
            switch($row->settings->run_interval_type) {
                case 'minutes':
                    $downtime_seconds_to_add = $row->settings->run_interval * 60;
                    break;

                case 'hours':
                    $downtime_seconds_to_add = $row->settings->run_interval * 60 * 60;
                    break;

                case 'days':
                    $downtime_seconds_to_add = $row->settings->run_interval * 60 * 60 * 24;
                    break;
            }
            $uptime_seconds = $row->uptime_seconds;
            $downtime_seconds = $row->downtime_seconds + $downtime_seconds_to_add;

            /* ^_^ */
            $uptime = $uptime_seconds > 0 ? $uptime_seconds / ($uptime_seconds + $downtime_seconds) * 100 : 0;
            $downtime = 100 - $uptime;
            $main_missed_datetime = $row->is_ok && !$is_ok ? \Altum\Date::$date : $row->main_missed_datetime;
            $last_missed_datetime = \Altum\Date::$date;

            /* Calculate expected next run */
            $next_run_datetime = (new \DateTime())
                ->modify('+' . $row->settings->run_interval . ' ' . $row->settings->run_interval_type)
                ->modify('+' . $row->settings->run_interval_grace . ' ' . $row->settings->run_interval_grace_type)
                ->format('Y-m-d H:i:s');

            /* Create / update an incident if needed */
            $incident_id = $row->incident_id;

            if(!$is_ok && !$row->incident_id) {

                /* Database query */
                $incident_id = db()->insert('incidents', [
                    'heartbeat_id' => $row->heartbeat_id,
                    'start_heartbeat_log_id' => $heartbeat_log_id,
                    'start_datetime' => \Altum\Date::$date,
                ]);

                if($row->plan_settings->email_notifications_is_enabled && $row->notifications->email_is_enabled) {
                    /* Get the language for the user and set the timezone */
                    $language = language($row->language);
                    \Altum\Date::$timezone = $row->timezone;

                    /* Prepare the email title */
                    $email_title = sprintf($language->cron->is_not_ok->title, $row->name);

                    /* Prepare the View for the email content */
                    $data = [
                        'language' => $language,
                        'row' => $row
                    ];

                    $email_content = (new \Altum\Views\View('partials/cron/heartbeat_is_not_ok', (array)$this))->run($data);

                    /* Send the email */
                    send_mail($row->email, $email_title, $email_content);
                }

                /* Webhook notification */
                if($row->notifications->webhook) {
                    try {
                        \Unirest\Request::post($row->notifications->webhook, [], [
                            'heartbeat_id' => $row->heartbeat_id,
                            'name' => $row->name,
                            'is_ok' => $is_ok,
                        ]);
                    } catch (\Exception $exception) {
                        // :)
                    }
                }

                /* Slack notification */
                if($row->notifications->slack) {
                    try {
                        \Unirest\Request::post(
                            $row->notifications->slack,
                            ['Accept' => 'application/json'],
                            \Unirest\Request\Body::json([
                                'text' => sprintf(language()->heartbeat->slack_notifications->is_not_ok, $row->name),
                                'username' => settings()->title,
                                'icon_emoji' => ':large_red_square:'
                            ])
                        );
                    } catch (\Exception $exception) {
                        // :)
                    }
                }

                /* Twilio Sms Notification */
                if(settings()->monitors_heartbeats->twilio_notifications_is_enabled && $row->plan_settings->twilio_notifications_is_enabled && $row->notifications->twilio) {
                    try {
                        \Unirest\Request::auth(settings()->monitors_heartbeats->twilio_sid, settings()->monitors_heartbeats->twilio_token);

                        \Unirest\Request::post(
                            sprintf('https://api.twilio.com/2010-04-01/Accounts/%s/Messages.json', settings()->monitors_heartbeats->twilio_sid),
                            [],
                            [
                                'From' => settings()->monitors_heartbeats->twilio_number,
                                'To' => $row->notifications->twilio,
                                'Body' => sprintf(language()->heartbeat->twilio_notifications->is_not_ok, $row->name),
                            ]
                        );
                    } catch (\Exception $exception) {
                        // :)
                    }

                    \Unirest\Request::auth('', '');
                }
            }

            /* Update the heartbeat */
            db()->where('heartbeat_id', $row->heartbeat_id)->update('heartbeats', [
                'incident_id' => $incident_id,
                'is_ok' => $is_ok,
                'uptime' => $uptime,
                'uptime_seconds' => $uptime_seconds,
                'downtime' => $downtime,
                'downtime_seconds' => $downtime_seconds,
                'total_missed_runs' => db()->inc(),
                'main_missed_datetime' => $main_missed_datetime,
                'last_missed_datetime' => $last_missed_datetime,
                'next_run_datetime' => $next_run_datetime,
            ]);

            /* Clear out old heartbeats logs */
            $x_days_ago_datetime = (new \DateTime())->modify('-' . ($row->plan_settings->logs_retention ?? 90) . ' days')->format('Y-m-d H:i:s');
            database()->query("DELETE FROM `heartbeats_logs` WHERE `datetime` < '{$x_days_ago_datetime}'");

            /* Clear the cache */
            \Altum\Cache::$adapter->deleteItemsByTag('heartbeat_id=' . $row->heartbeat_id);

        }

    }

    public function monitors_email_reports() {

        $this->initiate();

        $date = \Altum\Date::$date;

        /* Update cron job last run date */
        $new_cron = json_encode(array_merge((array) settings()->cron, ['monitors_email_reports_datetime' => $date]));
        db()->where('`key`', 'cron')->update('settings', ['value' => $new_cron]);

        /* Only run this part if the email reports are enabled */
        if(!settings()->monitors_heartbeats->email_reports_is_enabled) {
            return;
        }


        /* Determine the frequency of email reports */
        $days_interval = 7;

        switch(settings()->monitors_heartbeats->email_reports_is_enabled) {
            case 'weekly':
                $days_interval = 7;

                break;

            case 'monthly':
                $days_interval = 30;

                break;
        }

        /* Get potential monitors from users that have almost all the conditions to get an email report right now */
        $result = database()->query("
            SELECT
                `monitors`.`monitor_id`,
                `monitors`.`name`,
                `monitors`.`email_reports_last_datetime`,
                `users`.`user_id`,
                `users`.`email`,
                `users`.`plan_settings`,
                `users`.`language`
            FROM 
                `monitors`
            LEFT JOIN 
                `users` ON `monitors`.`user_id` = `users`.`user_id` 
            WHERE 
                `users`.`active` = 1
                AND `monitors`.`is_enabled` = 1 
                AND `monitors`.`email_reports_is_enabled` = 1
				AND DATE_ADD(`monitors`.`email_reports_last_datetime`, INTERVAL {$days_interval} DAY) <= '{$date}'
            LIMIT 25
        ");

        /* Go through each result */
        while($row = $result->fetch_object()) {
            $row->plan_settings = json_decode($row->plan_settings);

            /* Make sure the plan still lets the user get email reports */
            if(!$row->plan_settings->email_reports_is_enabled) {
                db()->where('monitor_id', $row->monitor_id)->update('monitors', ['email_reports_is_enabled' => 0]);

                continue;
            }

            /* Prepare */
            $start_date = (new \DateTime())->modify('-' . $days_interval . ' days')->format('Y-m-d H:i:s');

            /* Monitor logs */
            $monitor_logs = [];

            $monitor_logs_result = database()->query("
                SELECT 
                    `is_ok`,
                    `response_time`,
                    `datetime`
                FROM 
                    `monitors_logs`
                WHERE 
                    `monitor_id` = {$row->monitor_id} 
                    AND (`datetime` BETWEEN '{$start_date}' AND '{$date}')
            ");

            $total_ok_checks = 0;
            $total_not_ok_checks = 0;
            $total_response_time = 0;

            while($monitor_log = $monitor_logs_result->fetch_object()) {
                $monitor_logs[] = $monitor_log;

                $total_ok_checks = $monitor_log->is_ok ? $total_ok_checks + 1 : $total_ok_checks;
                $total_not_ok_checks = !$monitor_log->is_ok ? $total_not_ok_checks + 1 : $total_not_ok_checks;
                $total_response_time += $monitor_log->response_time;
            }

            /* Monitor incidents */
            $monitor_incidents = [];

            $monitor_incidents_result = database()->query("
                SELECT 
                    `start_datetime`,
                    `end_datetime`
                FROM 
                    `incidents`
                WHERE 
                    `monitor_id` = {$row->monitor_id} 
                    AND `start_datetime` >= '{$start_date}' 
                    AND `end_datetime` <= '{$date}'
            ");

            while($monitor_incident = $monitor_incidents_result->fetch_object()) {
                $monitor_incidents[] = $monitor_incident;
            }

            /* calculate some data */
            $total_monitor_logs = count($monitor_logs);
            $uptime = $total_ok_checks > 0 ? $total_ok_checks / ($total_ok_checks + $total_not_ok_checks) * 100 : 0;
            $downtime = 100 - $uptime;
            $average_response_time = $total_ok_checks > 0 ? $total_response_time / $total_ok_checks : 0;

            /* Get the language for the user */
            $language = language($row->language);

            /* Prepare the email title */
            $email_title = sprintf(
                $language->cron->monitor_email_report->title,
                $row->name,
                \Altum\Date::get($start_date, 5),
                \Altum\Date::get('', 5)
            );

            /* Prepare the View for the email content */
            $data = [
                'row'                       => $row,
                'language'                  => $language,
                'monitor_logs'              => $monitor_logs,
                'total_monitor_logs'        => $total_monitor_logs,
                'monitor_logs_data' => [
                    'uptime'                => $uptime,
                    'downtime'              => $downtime,
                    'average_response_time' => $average_response_time,
                    'total_ok_checks'       => $total_ok_checks,
                    'total_not_ok_checks'   => $total_not_ok_checks
                ],
                'monitor_incidents'         => $monitor_incidents,

                'start_date'                => $start_date,
                'end_date'                  => $date
            ];

            $email_content = (new \Altum\Views\View('partials/cron/monitor_email_report', (array) $this))->run($data);

            /* Send the email */
            send_mail($row->email, $email_title, $email_content);

            /* Update the store */
            db()->where('monitor_id', $row->monitor_id)->update('monitors', ['email_reports_last_datetime' => $date]);

            /* Insert email log */
            db()->insert('email_reports', ['user_id' => $row->user_id, 'monitor_id' => $row->monitor_id, 'datetime' => $date]);

            if(DEBUG) {
                echo sprintf('Email sent for user_id %s and monitor_id %s', $row->user_id, $row->monitor_id);
            }
        }

    }

    public function heartbeats_email_reports() {

        $this->initiate();

        $date = \Altum\Date::$date;

        /* Update cron job last run date */
        $new_cron = json_encode(array_merge((array) settings()->cron, ['heartbeats_email_reports_datetime' => $date]));
        db()->where('`key`', 'cron')->update('settings', ['value' => $new_cron]);

        /* Only run this part if the email reports are enabled */
        if(!settings()->monitors_heartbeats->email_reports_is_enabled) {
            return;
        }

        /* Determine the frequency of email reports */
        $days_interval = 7;

        switch(settings()->monitors_heartbeats->email_reports_is_enabled) {
            case 'weekly':
                $days_interval = 7;

                break;

            case 'monthly':
                $days_interval = 30;

                break;
        }

        /* Get potential heartbeats from users that have almost all the conditions to get an email report right now */
        $result = database()->query("
            SELECT
                `heartbeats`.`heartbeat_id`,
                `heartbeats`.`name`,
                `heartbeats`.`email_reports_last_datetime`,
                `users`.`user_id`,
                `users`.`email`,
                `users`.`plan_settings`,
                `users`.`language`
            FROM 
                `heartbeats`
            LEFT JOIN 
                `users` ON `heartbeats`.`user_id` = `users`.`user_id` 
            WHERE 
                `users`.`active` = 1
                AND `heartbeats`.`is_enabled` = 1 
                AND `heartbeats`.`email_reports_is_enabled` = 1
				AND DATE_ADD(`heartbeats`.`email_reports_last_datetime`, INTERVAL {$days_interval} DAY) <= '{$date}'
            LIMIT 25
        ");

        /* Go through each result */
        while($row = $result->fetch_object()) {
            $row->plan_settings = json_decode($row->plan_settings);

            /* Make sure the plan still lets the user get email reports */
            if(!$row->plan_settings->email_reports_is_enabled) {
                db()->where('heartbeat_id', $row->heartbeat_id)->update('heartbeats', ['email_reports_is_enabled' => 0]);

                continue;
            }

            /* Prepare */
            $start_date = (new \DateTime())->modify('-' . $days_interval . ' days')->format('Y-m-d H:i:s');

            /* Monitor logs */
            $heartbeat_logs = [];

            $heartbeat_logs_result = database()->query("
                SELECT 
                    `is_ok`,
                    `datetime`
                FROM 
                    `heartbeats_logs`
                WHERE 
                    `heartbeat_id` = {$row->heartbeat_id} 
                    AND (`datetime` BETWEEN '{$start_date}' AND '{$date}')
            ");

            $total_runs = 0;
            $total_missed_runs = 0;

            while($heartbeat_log = $heartbeat_logs_result->fetch_object()) {
                $heartbeat_logs[] = $heartbeat_log;

                $total_runs = $heartbeat_log->is_ok ? $total_runs + 1 : $total_runs;
                $total_missed_runs = !$heartbeat_log->is_ok ? $total_missed_runs + 1 : $total_missed_runs;
            }

            /* Monitor incidents */
            $heartbeat_incidents = [];

            $heartbeat_incidents_result = database()->query("
                SELECT 
                    `start_datetime`,
                    `end_datetime`
                FROM 
                    `incidents`
                WHERE 
                    `heartbeat_id` = {$row->heartbeat_id} 
                    AND `start_datetime` >= '{$start_date}' 
                    AND `end_datetime` <= '{$date}'
            ");

            while($heartbeat_incident = $heartbeat_incidents_result->fetch_object()) {
                $heartbeat_incidents[] = $heartbeat_incident;
            }

            /* calculate some data */
            $total_heartbeat_logs = count($heartbeat_logs);
            $uptime = $total_runs > 0 ? $total_runs / ($total_runs + $total_missed_runs) * 100 : 0;
            $downtime = 100 - $uptime;

            /* Get the language for the user */
            $language = language($row->language);

            /* Prepare the email title */
            $email_title = sprintf(
                $language->cron->heartbeat_email_report->title,
                $row->name,
                \Altum\Date::get($start_date, 5),
                \Altum\Date::get('', 5)
            );

            /* Prepare the View for the email content */
            $data = [
                'row'                       => $row,
                'language'                  => $language,
                'heartbeat_logs'            => $heartbeat_logs,
                'total_heartbeat_logs'      => $total_heartbeat_logs,
                'heartbeat_logs_data' => [
                    'uptime'                => $uptime,
                    'downtime'              => $downtime,
                    'total_runs'            => $total_runs,
                    'total_missed_runs'     => $total_missed_runs
                ],
                'heartbeat_incidents'       => $heartbeat_incidents,

                'start_date'                => $start_date,
                'end_date'                  => $date
            ];

            $email_content = (new \Altum\Views\View('partials/cron/heartbeat_email_report', (array) $this))->run($data);

            /* Send the email */
            send_mail($row->email, $email_title, $email_content);

            /* Update the store */
            db()->where('heartbeat_id', $row->heartbeat_id)->update('heartbeats', ['email_reports_last_datetime' => $date]);

            /* Insert email log */
            db()->insert('email_reports', ['user_id' => $row->user_id, 'heartbeat_id' => $row->heartbeat_id, 'datetime' => $date]);

            if(DEBUG) {
                echo sprintf('Email sent for user_id %s and heartbeat_id %s', $row->user_id, $row->heartbeat_id);
            }
        }

    }

}

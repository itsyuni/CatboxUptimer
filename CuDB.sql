--
-- Table structure for table `affiliates_commissions`
--

CREATE TABLE `affiliates_commissions` (
  `affiliate_commission_id` bigint(11) UNSIGNED NOT NULL,
  `user_id` bigint(11) UNSIGNED DEFAULT NULL,
  `referred_user_id` bigint(11) UNSIGNED DEFAULT NULL,
  `payment_id` bigint(11) UNSIGNED DEFAULT NULL,
  `amount` float DEFAULT NULL,
  `currency` varchar(4) DEFAULT NULL,
  `is_withdrawn` tinyint(4) UNSIGNED DEFAULT 0,
  `datetime` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `affiliates_withdrawals`
--

CREATE TABLE `affiliates_withdrawals` (
  `affiliate_withdrawal_id` bigint(11) UNSIGNED NOT NULL,
  `user_id` bigint(11) UNSIGNED DEFAULT NULL,
  `amount` float DEFAULT NULL,
  `currency` varchar(4) DEFAULT NULL,
  `note` varchar(1024) DEFAULT NULL,
  `affiliate_commissions_ids` text DEFAULT NULL,
  `is_paid` tinyint(4) UNSIGNED DEFAULT NULL,
  `datetime` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `codes`
--

CREATE TABLE `codes` (
  `code_id` int(11) UNSIGNED NOT NULL,
  `type` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `days` int(11) DEFAULT NULL COMMENT 'only applicable if type is redeemable',
  `plan_id` int(11) UNSIGNED DEFAULT NULL,
  `code` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `discount` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `redeemed` int(11) NOT NULL DEFAULT 0,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `domains`
--

CREATE TABLE `domains` (
  `domain_id` bigint(11) UNSIGNED NOT NULL,
  `status_page_id` bigint(11) UNSIGNED DEFAULT NULL,
  `user_id` bigint(11) UNSIGNED DEFAULT NULL,
  `scheme` varchar(8) NOT NULL DEFAULT '',
  `host` varchar(256) NOT NULL DEFAULT '',
  `custom_index_url` varchar(256) DEFAULT NULL,
  `custom_not_found_url` varchar(256) DEFAULT NULL,
  `type` tinyint(11) DEFAULT 1,
  `is_enabled` tinyint(4) DEFAULT 0,
  `datetime` datetime DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `email_reports`
--

CREATE TABLE `email_reports` (
  `id` bigint(11) UNSIGNED NOT NULL,
  `user_id` bigint(11) UNSIGNED NOT NULL,
  `monitor_id` bigint(11) UNSIGNED DEFAULT NULL,
  `heartbeat_id` bigint(11) UNSIGNED DEFAULT NULL,
  `datetime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `heartbeats`
--

CREATE TABLE `heartbeats` (
  `heartbeat_id` bigint(11) UNSIGNED NOT NULL,
  `project_id` bigint(11) UNSIGNED DEFAULT NULL,
  `user_id` bigint(11) UNSIGNED NOT NULL,
  `incident_id` bigint(11) UNSIGNED DEFAULT NULL,
  `name` varchar(256) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `settings` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_ok` tinyint(4) DEFAULT 1,
  `uptime` float DEFAULT 100,
  `uptime_seconds` int(11) UNSIGNED DEFAULT 0,
  `downtime` float DEFAULT 0,
  `downtime_seconds` int(11) UNSIGNED DEFAULT 0,
  `total_runs` bigint(11) UNSIGNED DEFAULT 0,
  `total_missed_runs` bigint(11) UNSIGNED DEFAULT 0,
  `main_run_datetime` datetime DEFAULT NULL,
  `last_run_datetime` datetime DEFAULT NULL,
  `next_run_datetime` datetime DEFAULT NULL,
  `main_missed_datetime` datetime DEFAULT NULL,
  `last_missed_datetime` datetime DEFAULT NULL,
  `notifications` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_reports_is_enabled` tinyint(4) DEFAULT 0,
  `email_reports_last_datetime` datetime DEFAULT NULL,
  `is_enabled` tinyint(4) NOT NULL DEFAULT 1,
  `last_datetime` datetime DEFAULT NULL,
  `datetime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `heartbeats_logs`
--

CREATE TABLE `heartbeats_logs` (
  `heartbeat_log_id` bigint(11) UNSIGNED NOT NULL,
  `heartbeat_id` bigint(11) UNSIGNED NOT NULL,
  `user_id` bigint(11) UNSIGNED DEFAULT NULL,
  `is_ok` tinyint(4) UNSIGNED DEFAULT 1,
  `datetime` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `incidents`
--

CREATE TABLE `incidents` (
  `incident_id` bigint(11) UNSIGNED NOT NULL,
  `monitor_id` bigint(11) UNSIGNED DEFAULT NULL,
  `start_monitor_log_id` bigint(11) UNSIGNED DEFAULT NULL,
  `end_monitor_log_id` bigint(11) UNSIGNED DEFAULT NULL,
  `heartbeat_id` bigint(11) UNSIGNED DEFAULT NULL,
  `start_heartbeat_log_id` bigint(11) UNSIGNED DEFAULT NULL,
  `end_heartbeat_log_id` bigint(11) UNSIGNED DEFAULT NULL,
  `start_datetime` datetime DEFAULT NULL,
  `end_datetime` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `monitors`
--

CREATE TABLE `monitors` (
  `monitor_id` bigint(11) UNSIGNED NOT NULL,
  `project_id` bigint(11) UNSIGNED DEFAULT NULL,
  `user_id` bigint(11) UNSIGNED NOT NULL,
  `ping_servers_ids` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `incident_id` bigint(11) UNSIGNED DEFAULT NULL,
  `name` varchar(256) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `target` varchar(1024) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `port` int(11) DEFAULT NULL,
  `settings` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `details` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ssl` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_ok` tinyint(4) DEFAULT 1,
  `uptime` float DEFAULT 100,
  `uptime_seconds` int(11) UNSIGNED DEFAULT 0,
  `downtime` float DEFAULT 0,
  `downtime_seconds` int(11) UNSIGNED DEFAULT 0,
  `average_response_time` float DEFAULT NULL,
  `total_checks` bigint(11) UNSIGNED DEFAULT 0,
  `total_ok_checks` bigint(11) UNSIGNED DEFAULT NULL,
  `total_not_ok_checks` bigint(11) UNSIGNED DEFAULT 0,
  `last_check_datetime` datetime DEFAULT NULL,
  `next_check_datetime` datetime DEFAULT NULL,
  `main_ok_datetime` datetime DEFAULT NULL,
  `last_ok_datetime` datetime DEFAULT NULL,
  `main_not_ok_datetime` datetime DEFAULT NULL,
  `last_not_ok_datetime` datetime DEFAULT NULL,
  `notifications` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_reports_is_enabled` tinyint(4) DEFAULT 0,
  `email_reports_last_datetime` datetime DEFAULT NULL,
  `is_enabled` tinyint(4) NOT NULL DEFAULT 1,
  `last_datetime` datetime DEFAULT NULL,
  `datetime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `monitors`
--

INSERT INTO `monitors` (`monitor_id`, `project_id`, `user_id`, `ping_servers_ids`, `incident_id`, `name`, `type`, `target`, `port`, `settings`, `details`, `ssl`, `is_ok`, `uptime`, `uptime_seconds`, `downtime`, `downtime_seconds`, `average_response_time`, `total_checks`, `total_ok_checks`, `total_not_ok_checks`, `last_check_datetime`, `next_check_datetime`, `main_ok_datetime`, `last_ok_datetime`, `main_not_ok_datetime`, `last_not_ok_datetime`, `notifications`, `email_reports_is_enabled`, `email_reports_last_datetime`, `is_enabled`, `last_datetime`, `datetime`) VALUES
(1, NULL, 1, '[1]', NULL, 'Example', 'website', 'https://example.com/', 0, '{\"check_interval_seconds\":3600,\"timeout_seconds\":3600,\"request_method\":\"GET\",\"request_body\":\"\",\"request_basic_auth_username\":\"\",\"request_basic_auth_password\":\"\",\"request_headers\":[],\"response_status_code\":200,\"response_body\":\"\",\"response_headers\":[]}', '{\"country_code\":\"US\",\"city_name\":\"Norwell\",\"continent_name\":\"North America\"}', NULL, 1, 100, 0, 0, 0, 0, 0, 0, 0, '2021-03-26 07:16:19', '2021-03-26 07:16:19', '2021-03-26 07:16:19', '2021-03-26 07:16:19', '2021-03-26 07:16:19', '2021-03-26 07:16:19', '{\"email_is_enabled\":0,\"webhook\":\"\",\"slack\":\"\",\"twilio\":\"\"}', 0, '2021-03-26 07:16:19', 1, NULL, '2021-03-26 07:16:19');

-- --------------------------------------------------------

--
-- Table structure for table `monitors_logs`
--

CREATE TABLE `monitors_logs` (
  `monitor_log_id` bigint(11) UNSIGNED NOT NULL,
  `monitor_id` bigint(11) UNSIGNED NOT NULL,
  `ping_server_id` bigint(11) UNSIGNED DEFAULT 1,
  `user_id` bigint(11) UNSIGNED DEFAULT NULL,
  `is_ok` tinyint(3) UNSIGNED DEFAULT NULL,
  `response_time` float DEFAULT 0,
  `response_status_code` int(11) UNSIGNED DEFAULT NULL,
  `error` text DEFAULT NULL,
  `datetime` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE `pages` (
  `page_id` bigint(11) UNSIGNED NOT NULL,
  `pages_category_id` bigint(11) UNSIGNED DEFAULT NULL,
  `url` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `description` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `position` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `order` int(11) DEFAULT 0,
  `total_views` int(11) DEFAULT 0,
  `date` datetime DEFAULT NULL,
  `last_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pages_categories`
--

CREATE TABLE `pages_categories` (
  `pages_category_id` bigint(11) UNSIGNED NOT NULL,
  `url` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `title` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `description` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `icon` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` bigint(11) UNSIGNED NOT NULL,
  `user_id` bigint(11) UNSIGNED DEFAULT NULL,
  `plan_id` int(11) UNSIGNED DEFAULT NULL,
  `processor` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `frequency` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_id` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subscription_id` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payer_id` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(256) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(256) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `billing` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `taxes_ids` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `base_amount` float DEFAULT NULL,
  `total_amount` float DEFAULT NULL,
  `code` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `discount_amount` float DEFAULT NULL,
  `currency` varchar(4) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_proof` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(4) DEFAULT 1,
  `date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ping_servers`
--

CREATE TABLE `ping_servers` (
  `ping_server_id` bigint(20) UNSIGNED NOT NULL,
  `url` varchar(1024) DEFAULT NULL,
  `name` varchar(64) DEFAULT NULL,
  `country_code` varchar(8) DEFAULT NULL,
  `city_name` varchar(64) DEFAULT NULL,
  `is_enabled` tinyint(4) DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  `datetime` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `ping_servers`
--

INSERT INTO `ping_servers` (`ping_server_id`, `url`, `name`, `country_code`, `city_name`, `is_enabled`, `last_datetime`, `datetime`) VALUES
(1, '', 'Default', 'US', 'New-York', 1, NULL, '2021-03-26 07:16:19');

-- --------------------------------------------------------

--
-- Table structure for table `plans`
--

CREATE TABLE `plans` (
  `plan_id` int(11) UNSIGNED NOT NULL,
  `name` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `monthly_price` float DEFAULT NULL,
  `annual_price` float DEFAULT NULL,
  `lifetime_price` float DEFAULT NULL,
  `settings` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `taxes_ids` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(4) NOT NULL,
  `order` int(10) UNSIGNED DEFAULT 0,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(11) UNSIGNED DEFAULT NULL,
  `name` varchar(128) DEFAULT NULL,
  `color` varchar(16) DEFAULT '#000',
  `last_datetime` datetime DEFAULT NULL,
  `datetime` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `redeemed_codes`
--

CREATE TABLE `redeemed_codes` (
  `id` bigint(11) UNSIGNED NOT NULL,
  `code_id` int(11) UNSIGNED NOT NULL,
  `user_id` bigint(11) UNSIGNED NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `key` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `value` longtext COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `key`, `value`) VALUES
(1, 'ads', '{\"header\":\"\",\"footer\":\"\",\"header_status_pages\":\"\",\"footer_status_pages\":\"\"}'),
(2, 'captcha', '{\"type\":\"basic\",\"recaptcha_public_key\":\"\",\"recaptcha_private_key\":\"\",\"hcaptcha_site_key\":\"\",\"hcaptcha_secret_key\":\"\",\"login_is_enabled\":\"\",\"register_is_enabled\":\"\",\"lost_password_is_enabled\":\"\",\"resend_activation_is_enabled\":\"\"}'),
(3, 'cron', '{\"key\":\"4a80eaa4d7b8d50f8927cabe540c8b13\",\"reset_date\":\"2021-03-26 07:16:19\"}'),
(4, 'default_language', 'english'),
(5, 'default_theme_style', 'light'),
(6, 'email_confirmation', '0'),
(7, 'register_is_enabled', '1'),
(8, 'email_notifications', '{\"emails\":\"\",\"new_user\":\"\",\"new_payment\":\"\",\"new_domain\":\"\"}'),
(9, 'facebook', '{\"is_enabled\":\"\",\"app_id\":\"\",\"app_secret\":\"\"}'),
(10, 'favicon', ''),
(11, 'logo', ''),
(12, 'plan_custom', '{\"plan_id\":\"custom\",\"name\":\"Custom\",\"status\":1}'),
(13, 'plan_free', '{\"plan_id\":\"free\",\"name\":\"Free\",\"days\":null,\"status\":1,\"settings\":{\"monitors_limit\":1,\"projects_limit\":5,\"categories_limit\":50,\"items_limit\":50,\"no_ads\":true,\"analytics_is_enabled\":true,\"ordering_is_enabled\":true,\"removable_branding_is_enabled\":false,\"custom_url_is_enabled\":true,\"password_protection_is_enabled\":true,\"search_engine_block_is_enabled\":false,\"custom_css_is_enabled\":false,\"custom_js_is_enabled\":false,\"email_reports_is_enabled\":false}}'),
(14, 'plan_trial', '{\"plan_id\":\"trial\",\"name\":\"Trial\",\"days\":7,\"status\":0,\"settings\":{\"monitors_limit\":10,\"projects_limit\":50,\"categories_limit\":50,\"items_limit\":50,\"no_ads\":true,\"analytics_is_enabled\":true,\"ordering_is_enabled\":true,\"removable_branding_is_enabled\":true,\"custom_url_is_enabled\":true,\"password_protection_is_enabled\":true,\"search_engine_block_is_enabled\":true,\"custom_css_is_enabled\":false,\"custom_js_is_enabled\":false}}'),
(15, 'payment', '{\"is_enabled\":\"1\",\"type\":\"both\",\"brand_name\":\":)\",\"currency\":\"USD\",\"codes_is_enabled\":\"1\",\"taxes_and_billing_is_enabled\":\"\"}'),
(16, 'paypal', '{\"is_enabled\":\"\",\"mode\":\"sandbox\",\"client_id\":\"\",\"secret\":\"\"}'),
(17, 'stripe', '{\"is_enabled\":\"\",\"publishable_key\":\"\",\"secret_key\":\"\",\"webhook_secret\":\"\"}'),
(18, 'offline_payment', '{\"is_enabled\":\"\",\"instructions\":\"Your offline payment instructions go here..\"}'),
(19, 'smtp', '{\"from_name\":\"\",\"from\":\"\",\"host\":\"\",\"encryption\":\"tls\",\"port\":\"587\",\"auth\":\"1\",\"username\":\"\",\"password\":\"\"}'),
(20, 'custom', '{\"head_js\":\"\",\"head_css\":\"\"}'),
(21, 'socials', '{\"youtube\":\"\",\"facebook\":\"\",\"twitter\":\"\",\"instagram\":\"\"}'),
(22, 'default_timezone', 'UTC'),
(23, 'title', '11Uptime'),
(24, 'privacy_policy_url', ''),
(25, 'terms_and_conditions_url', ''),
(26, 'index_url', ''),
(27, 'business', '{\"invoice_is_enabled\":\"\",\"invoice_nr_prefix\":\"\",\"name\":\"\",\"address\":\"\",\"city\":\"\",\"county\":\"\",\"zip\":\"\",\"country\":\"AF\",\"email\":\"\",\"phone\":\"\",\"tax_type\":\"\",\"tax_id\":\"\",\"custom_key_one\":\"\",\"custom_value_one\":\"\",\"custom_key_two\":\"\",\"custom_value_two\":\"\"}'),
(28, 'webhooks', '{\"user_new\": \"\", \"user_delete\": \"\"}'),
(29, 'status_pages', '{\"domains_is_enabled\":\"1\",\"additional_domains_is_enabled\":\"1\",\"main_domain_is_enabled\":\"1\",\"logo_size_limit\":\"2\",\"favicon_size_limit\":\"2\"}'),
(30, 'affiliate', '{\"is_enabled\":\"1\",\"commission_type\":\"forever\",\"minimum_withdrawal_amount\":\"1\",\"commission_percentage\":\"25\",\"withdrawal_notes\":\"\"}'),
(31, 'license', '{\"license\": \"prowebber.ru\", \"type\": \"extended\"}'),
(32, 'product_info', '{\"version\":\"4.2.0\", \"code\":\"420\"}'),
(33, 'monitors_heartbeats', '{\"email_reports_is_enabled\":\"0\",\"monitors_ping_method\":\"exec\",\"twilio_notifications_is_enabled\":\"\",\"twilio_sid\":\"\",\"twilio_token\":\"\",\"twilio_number\":\"\"}'),
(34, 'announcements', '{\"id\":\"d41d8cd98f00b204e9800998ecf8427e\",\"content\":\"\",\"text_color\":\"#000000\",\"background_color\":\"#000000\",\"show_logged_in\":\"\",\"show_logged_out\":\"\"}'),
(35, 'opengraph', '');

-- --------------------------------------------------------

--
-- Table structure for table `statistics`
--

CREATE TABLE `statistics` (
  `id` bigint(11) UNSIGNED NOT NULL,
  `status_page_id` bigint(11) UNSIGNED NOT NULL,
  `project_id` bigint(11) UNSIGNED DEFAULT NULL,
  `country_code` varchar(8) DEFAULT NULL,
  `os_name` varchar(16) DEFAULT NULL,
  `city_name` varchar(128) DEFAULT NULL,
  `browser_name` varchar(32) DEFAULT NULL,
  `referrer_host` varchar(256) DEFAULT NULL,
  `referrer_path` varchar(1024) DEFAULT NULL,
  `device_type` varchar(16) DEFAULT NULL,
  `browser_language` varchar(16) DEFAULT NULL,
  `utm_source` varchar(128) DEFAULT NULL,
  `utm_medium` varchar(128) DEFAULT NULL,
  `utm_campaign` varchar(128) DEFAULT NULL,
  `is_unique` tinyint(4) DEFAULT 0,
  `datetime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `status_pages`
--

CREATE TABLE `status_pages` (
  `status_page_id` bigint(11) UNSIGNED NOT NULL,
  `domain_id` bigint(11) UNSIGNED DEFAULT NULL,
  `monitors_ids` text DEFAULT NULL,
  `project_id` bigint(11) UNSIGNED DEFAULT NULL,
  `user_id` bigint(11) UNSIGNED DEFAULT NULL,
  `url` varchar(128) DEFAULT NULL,
  `name` varchar(256) DEFAULT NULL,
  `description` varchar(256) DEFAULT NULL,
  `socials` text DEFAULT NULL,
  `logo` varchar(40) DEFAULT NULL,
  `favicon` varchar(40) DEFAULT NULL,
  `password` varchar(128) DEFAULT NULL,
  `timezone` varchar(32) DEFAULT NULL,
  `theme` varchar(16) DEFAULT NULL,
  `custom_js` text DEFAULT NULL,
  `custom_css` text DEFAULT NULL,
  `pageviews` bigint(11) UNSIGNED DEFAULT 0,
  `is_se_visible` tinyint(4) UNSIGNED DEFAULT 1,
  `is_removed_branding` tinyint(4) UNSIGNED DEFAULT 0,
  `is_enabled` tinyint(4) UNSIGNED DEFAULT 1,
  `last_datetime` datetime DEFAULT NULL,
  `datetime` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `status_pages`
--

INSERT INTO `status_pages` (`status_page_id`, `domain_id`, `monitors_ids`, `project_id`, `user_id`, `url`, `name`, `description`, `socials`, `logo`, `favicon`, `password`, `timezone`, `theme`, `custom_js`, `custom_css`, `pageviews`, `is_se_visible`, `is_removed_branding`, `is_enabled`, `last_datetime`, `datetime`) VALUES
(1, NULL, '[1]', NULL, 1, 'example', 'Example', 'This is just a simple description for the example status page ðŸ‘‹.', '{\"facebook\":\"example\",\"instagram\":\"example\",\"twitter\":\"example\",\"email\":\"\",\"website\":\"\"}', NULL, NULL, NULL, 'UTC', 'new-york', '', '', 0, 1, 0, 1, NULL, '2021-03-26 07:16:19');

-- --------------------------------------------------------

--
-- Table structure for table `taxes`
--

CREATE TABLE `taxes` (
  `tax_id` int(11) UNSIGNED NOT NULL,
  `internal_name` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(256) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `value` int(11) DEFAULT NULL,
  `value_type` enum('percentage','fixed') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` enum('inclusive','exclusive') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `billing_type` enum('personal','business','both') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `countries` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `datetime` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` bigint(11) UNSIGNED NOT NULL,
  `email` varchar(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `billing` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `api_key` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `token_code` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `twofa_secret` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `one_time_login_code` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pending_email` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_activation_code` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `lost_password_code` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `facebook_id` bigint(20) DEFAULT NULL,
  `type` int(11) NOT NULL DEFAULT 0,
  `active` int(11) NOT NULL DEFAULT 0,
  `plan_id` varchar(16) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `plan_expiration_date` datetime DEFAULT NULL,
  `plan_settings` text CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `plan_trial_done` tinyint(4) DEFAULT 0,
  `plan_expiry_reminder` tinyint(4) NOT NULL DEFAULT 0,
  `payment_subscription_id` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `referral_key` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `referred_by` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `referred_by_has_converted` tinyint(4) DEFAULT 0,
  `language` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT 'english',
  `timezone` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT 'UTC',
  `date` datetime DEFAULT NULL,
  `ip` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `country` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_activity` datetime DEFAULT NULL,
  `last_user_agent` text CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `total_logins` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `email`, `password`, `name`, `billing`, `api_key`, `token_code`, `twofa_secret`, `one_time_login_code`, `pending_email`, `email_activation_code`, `lost_password_code`, `facebook_id`, `type`, `active`, `plan_id`, `plan_expiration_date`, `plan_settings`, `plan_trial_done`, `plan_expiry_reminder`, `payment_subscription_id`, `referral_key`, `referred_by`, `referred_by_has_converted`, `language`, `timezone`, `date`, `ip`, `country`, `last_activity`, `last_user_agent`, `total_logins`) VALUES
(1, 'admin', '$2y$10$uFNO0pQKEHSFcus1zSFlveiPCB3EvG9ZlES7XKgJFTAl5JbRGFCWy', 'AltumCode', '{\"type\":\"personal\",\"name\":\"\",\"address\":\"\",\"city\":\"\",\"county\":\"\",\"zip\":\"\",\"country\":\"AF\",\"phone\":\"\",\"tax_id\":\"\"}', '3bfb8c11d91a73d4b32844366a583f74', '', NULL, NULL, NULL, '', '', NULL, 1, 1, 'custom', '2050-01-11 00:00:00', '{\"monitors_limit\":-1,\"heartbeats_limit\":3,\"status_pages_limit\":-1,\"projects_limit\":-1,\"domains_limit\":-1,\"additional_domains_is_enabled\":true,\"analytics_is_enabled\":true,\"removable_branding_is_enabled\":true,\"custom_url_is_enabled\":true,\"password_protection_is_enabled\":true,\"search_engine_block_is_enabled\":true,\"custom_css_is_enabled\":true,\"custom_js_is_enabled\":true,\"email_reports_is_enabled\":true,\"email_notifications_is_enabled\":true,\"no_ads\":true}', 0, 0, '', '92f9aedadc844c4579770bbbd2aedc60', NULL, 0, 'english', 'UTC', '2021-03-26 07:16:19', '::1', NULL, '2021-06-17 21:14:42', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.101 Safari/537.36', 4);

-- --------------------------------------------------------

--
-- Table structure for table `users_logs`
--

CREATE TABLE `users_logs` (
  `id` bigint(11) UNSIGNED NOT NULL,
  `user_id` bigint(11) UNSIGNED DEFAULT NULL,
  `type` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `ip` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `public` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users_logs`
--

INSERT INTO `users_logs` (`id`, `user_id`, `type`, `date`, `ip`, `public`) VALUES
(1, 1, 'login.success', '2021-03-26 11:24:14', '1.55.211.152', 1),
(2, 1, 'login.success', '2021-03-26 11:32:43', '34.64.117.89', 1),
(3, 1, 'login.success', '2021-05-20 15:17:16', '::1', 1),
(4, 1, 'login.success', '2021-06-17 21:06:09', '::1', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `affiliates_commissions`
--
ALTER TABLE `affiliates_commissions`
  ADD PRIMARY KEY (`affiliate_commission_id`),
  ADD UNIQUE KEY `affiliate_commission_id` (`affiliate_commission_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `referred_user_id` (`referred_user_id`),
  ADD KEY `payment_id` (`payment_id`);

--
-- Indexes for table `affiliates_withdrawals`
--
ALTER TABLE `affiliates_withdrawals`
  ADD PRIMARY KEY (`affiliate_withdrawal_id`),
  ADD UNIQUE KEY `affiliate_withdrawal_id` (`affiliate_withdrawal_id`);

--
-- Indexes for table `codes`
--
ALTER TABLE `codes`
  ADD PRIMARY KEY (`code_id`),
  ADD KEY `type` (`type`),
  ADD KEY `code` (`code`),
  ADD KEY `plan_id` (`plan_id`);

--
-- Indexes for table `domains`
--
ALTER TABLE `domains`
  ADD PRIMARY KEY (`domain_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `domains_host_index` (`host`),
  ADD KEY `domains_type_index` (`type`),
  ADD KEY `domains_ibfk_2` (`status_page_id`);

--
-- Indexes for table `email_reports`
--
ALTER TABLE `email_reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `datetime` (`datetime`),
  ADD KEY `monitor_id` (`monitor_id`),
  ADD KEY `heartbeat_id` (`heartbeat_id`);

--
-- Indexes for table `heartbeats`
--
ALTER TABLE `heartbeats`
  ADD PRIMARY KEY (`heartbeat_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `project_id` (`project_id`),
  ADD KEY `monitor_incident_id` (`incident_id`),
  ADD KEY `heartbeats_code_idx` (`code`) USING BTREE;

--
-- Indexes for table `heartbeats_logs`
--
ALTER TABLE `heartbeats_logs`
  ADD PRIMARY KEY (`heartbeat_log_id`),
  ADD UNIQUE KEY `monitors_log_id` (`heartbeat_log_id`) USING BTREE,
  ADD KEY `heartbeat_id` (`heartbeat_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `datetime` (`datetime`) USING BTREE;

--
-- Indexes for table `incidents`
--
ALTER TABLE `incidents`
  ADD PRIMARY KEY (`incident_id`),
  ADD UNIQUE KEY `monitor_incident_id` (`incident_id`),
  ADD KEY `start_monitor_log_id` (`start_monitor_log_id`),
  ADD KEY `end_monitor_log_id` (`end_monitor_log_id`),
  ADD KEY `monitor_id` (`monitor_id`),
  ADD KEY `heartbeat_id` (`heartbeat_id`),
  ADD KEY `start_heartbeat_log_id` (`start_heartbeat_log_id`),
  ADD KEY `end_heartbeat_log_id` (`end_heartbeat_log_id`);

--
-- Indexes for table `monitors`
--
ALTER TABLE `monitors`
  ADD PRIMARY KEY (`monitor_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `project_id` (`project_id`),
  ADD KEY `monitor_incident_id` (`incident_id`);

--
-- Indexes for table `monitors_logs`
--
ALTER TABLE `monitors_logs`
  ADD PRIMARY KEY (`monitor_log_id`),
  ADD UNIQUE KEY `monitors_log_id` (`monitor_log_id`) USING BTREE,
  ADD KEY `monitor_id` (`monitor_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `datetime` (`datetime`) USING BTREE,
  ADD KEY `ping_server_id` (`ping_server_id`);

--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`page_id`),
  ADD KEY `pages_pages_category_id_index` (`pages_category_id`),
  ADD KEY `pages_url_index` (`url`);

--
-- Indexes for table `pages_categories`
--
ALTER TABLE `pages_categories`
  ADD PRIMARY KEY (`pages_category_id`),
  ADD KEY `url` (`url`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payments_user_id` (`user_id`),
  ADD KEY `plan_id` (`plan_id`);

--
-- Indexes for table `ping_servers`
--
ALTER TABLE `ping_servers`
  ADD PRIMARY KEY (`ping_server_id`),
  ADD UNIQUE KEY `ping_server_id` (`ping_server_id`);

--
-- Indexes for table `plans`
--
ALTER TABLE `plans`
  ADD PRIMARY KEY (`plan_id`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`project_id`),
  ADD UNIQUE KEY `project_id` (`project_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `redeemed_codes`
--
ALTER TABLE `redeemed_codes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `code_id` (`code_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `key` (`key`);

--
-- Indexes for table `statistics`
--
ALTER TABLE `statistics`
  ADD PRIMARY KEY (`id`),
  ADD KEY `status_page_id` (`status_page_id`),
  ADD KEY `datetime` (`datetime`),
  ADD KEY `project_id` (`project_id`);

--
-- Indexes for table `status_pages`
--
ALTER TABLE `status_pages`
  ADD PRIMARY KEY (`status_page_id`),
  ADD UNIQUE KEY `status_page_id` (`status_page_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `domain_id` (`domain_id`),
  ADD KEY `project_id` (`project_id`);

--
-- Indexes for table `taxes`
--
ALTER TABLE `taxes`
  ADD PRIMARY KEY (`tax_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `plan_id` (`plan_id`),
  ADD KEY `api_key` (`api_key`);

--
-- Indexes for table `users_logs`
--
ALTER TABLE `users_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `users_logs_user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `affiliates_commissions`
--
ALTER TABLE `affiliates_commissions`
  MODIFY `affiliate_commission_id` bigint(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `affiliates_withdrawals`
--
ALTER TABLE `affiliates_withdrawals`
  MODIFY `affiliate_withdrawal_id` bigint(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `codes`
--
ALTER TABLE `codes`
  MODIFY `code_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `domains`
--
ALTER TABLE `domains`
  MODIFY `domain_id` bigint(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `email_reports`
--
ALTER TABLE `email_reports`
  MODIFY `id` bigint(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `heartbeats`
--
ALTER TABLE `heartbeats`
  MODIFY `heartbeat_id` bigint(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `heartbeats_logs`
--
ALTER TABLE `heartbeats_logs`
  MODIFY `heartbeat_log_id` bigint(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `incidents`
--
ALTER TABLE `incidents`
  MODIFY `incident_id` bigint(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `monitors`
--
ALTER TABLE `monitors`
  MODIFY `monitor_id` bigint(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `monitors_logs`
--
ALTER TABLE `monitors_logs`
  MODIFY `monitor_log_id` bigint(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `pages`
  MODIFY `page_id` bigint(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pages_categories`
--
ALTER TABLE `pages_categories`
  MODIFY `pages_category_id` bigint(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ping_servers`
--
ALTER TABLE `ping_servers`
  MODIFY `ping_server_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `plans`
--
ALTER TABLE `plans`
  MODIFY `plan_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `project_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `redeemed_codes`
--
ALTER TABLE `redeemed_codes`
  MODIFY `id` bigint(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `statistics`
--
ALTER TABLE `statistics`
  MODIFY `id` bigint(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `status_pages`
--
ALTER TABLE `status_pages`
  MODIFY `status_page_id` bigint(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `taxes`
--
ALTER TABLE `taxes`
  MODIFY `tax_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` bigint(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users_logs`
--
ALTER TABLE `users_logs`
  MODIFY `id` bigint(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `affiliates_commissions`
--
ALTER TABLE `affiliates_commissions`
  ADD CONSTRAINT `affiliates_commissions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `affiliates_commissions_ibfk_2` FOREIGN KEY (`referred_user_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `affiliates_commissions_ibfk_3` FOREIGN KEY (`payment_id`) REFERENCES `payments` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `codes`
--
ALTER TABLE `codes`
  ADD CONSTRAINT `codes_ibfk_1` FOREIGN KEY (`plan_id`) REFERENCES `plans` (`plan_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `email_reports`
--
ALTER TABLE `email_reports`
  ADD CONSTRAINT `email_reports_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `email_reports_ibfk_2` FOREIGN KEY (`monitor_id`) REFERENCES `monitors` (`monitor_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `email_reports_ibfk_3` FOREIGN KEY (`heartbeat_id`) REFERENCES `heartbeats` (`heartbeat_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `heartbeats`
--
ALTER TABLE `heartbeats`
  ADD CONSTRAINT `heartbeats_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `heartbeats_ibfk_2` FOREIGN KEY (`project_id`) REFERENCES `projects` (`project_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `heartbeats_ibfk_3` FOREIGN KEY (`incident_id`) REFERENCES `incidents` (`incident_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `heartbeats_logs`
--
ALTER TABLE `heartbeats_logs`
  ADD CONSTRAINT `heartbeats_logs_ibfk_1` FOREIGN KEY (`heartbeat_id`) REFERENCES `heartbeats` (`heartbeat_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `heartbeats_logs_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `incidents`
--
ALTER TABLE `incidents`
  ADD CONSTRAINT `incidents_ibfk_1` FOREIGN KEY (`start_monitor_log_id`) REFERENCES `monitors_logs` (`monitor_log_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `incidents_ibfk_2` FOREIGN KEY (`end_monitor_log_id`) REFERENCES `monitors_logs` (`monitor_log_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `incidents_ibfk_3` FOREIGN KEY (`monitor_id`) REFERENCES `monitors` (`monitor_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `incidents_ibfk_4` FOREIGN KEY (`heartbeat_id`) REFERENCES `heartbeats` (`heartbeat_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `incidents_ibfk_5` FOREIGN KEY (`start_heartbeat_log_id`) REFERENCES `heartbeats_logs` (`heartbeat_log_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `incidents_ibfk_6` FOREIGN KEY (`end_heartbeat_log_id`) REFERENCES `heartbeats_logs` (`heartbeat_log_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `monitors`
--
ALTER TABLE `monitors`
  ADD CONSTRAINT `monitors_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `monitors_ibfk_2` FOREIGN KEY (`project_id`) REFERENCES `projects` (`project_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `monitors_ibfk_3` FOREIGN KEY (`incident_id`) REFERENCES `incidents` (`incident_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `monitors_logs`
--
ALTER TABLE `monitors_logs`
  ADD CONSTRAINT `monitors_logs_ibfk_1` FOREIGN KEY (`monitor_id`) REFERENCES `monitors` (`monitor_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `monitors_logs_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `monitors_logs_ibfk_4` FOREIGN KEY (`ping_server_id`) REFERENCES `ping_servers` (`ping_server_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pages`
--
ALTER TABLE `pages`
  ADD CONSTRAINT `pages_ibfk_1` FOREIGN KEY (`pages_category_id`) REFERENCES `pages_categories` (`pages_category_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_plans_plan_id_fk` FOREIGN KEY (`plan_id`) REFERENCES `plans` (`plan_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `payments_users_user_id_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `projects`
--
ALTER TABLE `projects`
  ADD CONSTRAINT `projects_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `redeemed_codes`
--
ALTER TABLE `redeemed_codes`
  ADD CONSTRAINT `redeemed_codes_ibfk_1` FOREIGN KEY (`code_id`) REFERENCES `codes` (`code_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `redeemed_codes_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `statistics`
--
ALTER TABLE `statistics`
  ADD CONSTRAINT `statistics_ibfk_1` FOREIGN KEY (`status_page_id`) REFERENCES `status_pages` (`status_page_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `statistics_ibfk_2` FOREIGN KEY (`project_id`) REFERENCES `projects` (`project_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `statistics_ibfk_3` FOREIGN KEY (`project_id`) REFERENCES `status_pages` (`project_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `status_pages`
--
ALTER TABLE `status_pages`
  ADD CONSTRAINT `status_pages_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `status_pages_ibfk_2` FOREIGN KEY (`domain_id`) REFERENCES `domains` (`domain_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `status_pages_ibfk_3` FOREIGN KEY (`project_id`) REFERENCES `projects` (`project_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `users_logs`
--
ALTER TABLE `users_logs`
  ADD CONSTRAINT `users_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;
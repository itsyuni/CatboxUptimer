UPDATE `settings` SET `value` = '{\"version\":\"2.0.0\", \"code\":\"200\"}' WHERE `key` = 'product_info';

-- SEPARATOR --

alter table domains add custom_not_found_url varchar(256) null after custom_index_url;

-- SEPARATOR --

UPDATE `settings` SET `value` = '{"domains_is_enabled":"1","additional_domains_is_enabled":"1","main_domain_is_enabled":"1","logo_size_limit":"2","favicon_size_limit":"2"}', `key` = 'status_pages' WHERE `key` = 'monitors_and_status_pages';

-- SEPARATOR --

INSERT INTO `settings` (`key`, `value`) VALUES ('monitors_heartbeats', '{"email_reports_is_enabled":"0","monitors_ping_method":"exec","twilio_notifications_is_enabled":"0","twilio_sid":"","twilio_token":"","twilio_number":""}');

-- SEPARATOR --

alter table monitors change email_notifications_is_enabled notifications text null;

-- SEPARATOR --

alter table heartbeats change email_notifications_is_enabled notifications text null;

-- SEPARATOR --

UPDATE `monitors` SET `notifications` = '{"email_is_enabled":0,"webhook":"","slack":"","twilio":""}';

-- SEPARATOR --

UPDATE `heartbeats` SET `notifications` = '{"email_is_enabled":0,"webhook":"","slack":"","twilio":""}';

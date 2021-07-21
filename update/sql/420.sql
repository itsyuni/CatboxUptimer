UPDATE `settings` SET `value` = '{\"version\":\"4.2.0\", \"code\":\"420\"}' WHERE `key` = 'product_info';

-- SEPARATOR --

ALTER TABLE `users` ADD `plan_expiry_reminder` TINYINT NOT NULL DEFAULT '0' AFTER `plan_trial_done`;

UPDATE `settings` SET `value` = '{\"version\":\"2.1.0\", \"code\":\"210\"}' WHERE `key` = 'product_info';

-- SEPARATOR --

INSERT INTO `settings` (`key`, `value`) VALUES ('announcements', '{"id":"","content":"","show_logged_in":"","show_logged_out":""}');

UPDATE `settings` SET `value` = '{\"version\":\"3.0.0\", \"code\":\"300\"}' WHERE `key` = 'product_info';

-- SEPARATOR --

INSERT INTO `settings` (`key`, `value`) VALUES ('opengraph', '');

-- SEPARATOR --

alter table monitors add `ssl` text null after details;

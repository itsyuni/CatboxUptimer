UPDATE `settings` SET `value` = '{\"version\":\"4.0.0\", \"code\":\"400\"}' WHERE `key` = 'product_info';

-- SEPARATOR --

alter table monitors_logs add error text null after response_status_code;

-- SEPARATOR --

alter table plans add `order` int unsigned default 0 null after status;

-- SEPARATOR --

SET @rownumber = 0;

-- SEPARATOR --

update plans set `order` = @rownumber := @rownumber+1 order by plan_id;

ALTER TABLE `pos_cart` CHANGE `customer_id` `customer_id` INT(11) NULL;
ALTER TABLE `pos_order` CHANGE `checkout_type` `checkout_type` INT(11) NULL;


ALTER TABLE `pos_order` CHANGE `shipping_cost_service_name` `shipping_cost_service_name` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL, CHANGE `shipping_cost_service_code` `shipping_cost_service_code` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;

ALTER TABLE `pos_order` CHANGE `intent_data` `intent_data` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL;


ALTER TABLE `pos_order` CHANGE `pick_up_id` `pick_up_id` INT(11) NULL DEFAULT NULL;

ALTER TABLE `customer` CHANGE `customer_type` `customer_type` INT(11) NULL;
ALTER TABLE `customer` CHANGE `company_name` `company_name` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL;


ALTER TABLE `pos_order` CHANGE `shipping_cost` `shipping_cost` FLOAT NULL DEFAULT '0';
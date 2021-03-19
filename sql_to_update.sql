ALTER TABLE `pos_cart` CHANGE `customer_id` `customer_id` INT(11) NULL;
ALTER TABLE `pos_order` CHANGE `checkout_type` `checkout_type` INT(11) NULL;


ALTER TABLE `pos_order` CHANGE `shipping_cost_service_name` `shipping_cost_service_name` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL, CHANGE `shipping_cost_service_code` `shipping_cost_service_code` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;

ALTER TABLE `pos_order` CHANGE `intent_data` `intent_data` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL;


ALTER TABLE `pos_order` CHANGE `pick_up_id` `pick_up_id` INT(11) NULL DEFAULT NULL;

ALTER TABLE `customer` CHANGE `customer_type` `customer_type` INT(11) NULL;
ALTER TABLE `customer` CHANGE `company_name` `company_name` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL;


ALTER TABLE `pos_order` CHANGE `shipping_cost` `shipping_cost` FLOAT NULL DEFAULT '0';

ALTER TABLE `pos_order` CHANGE `cash_amount` `cash_amount` FLOAT NULL DEFAULT '0', CHANGE `credit_amount` `credit_amount` FLOAT NULL DEFAULT '0', CHANGE `is_split` `is_split` INT(11) NULL DEFAULT '0';

ALTER TABLE `pos_order_items` CHANGE `product_id` `product_id` INT(11) NULL, CHANGE `category_id` `category_id` INT(11) NULL, CHANGE `product_name` `product_name` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `amount` `amount` FLOAT NULL DEFAULT '0', CHANGE `product_unit_price` `product_unit_price` FLOAT NULL DEFAULT '0', CHANGE `quantity` `quantity` INT(11) NULL DEFAULT '0', CHANGE `order_id` `order_id` INT(11) NULL, CHANGE `pos_user_id` `pos_user_id` INT(11) NULL, CHANGE `store_id` `store_id` INT(11) NULL, CHANGE `manifest_id` `manifest_id` INT(11) NULL;


ALTER TABLE `pos_order` CHANGE `store_id` `store_id` INT(11) NULL, CHANGE `is_picked` `is_picked` INT(11) NULL DEFAULT '0', CHANGE `is_shipped` `is_shipped` INT(11) NULL DEFAULT '0', CHANGE `billing_name` `billing_name` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `billing_address` `billing_address` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `billing_country` `billing_country` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `billing_state` `billing_state` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `billing_city` `billing_city` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `billing_zip` `billing_zip` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `shipping_name` `shipping_name` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `coupon_log_id` `coupon_log_id` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `shipping_address` `shipping_address` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `shipping_country` `shipping_country` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `shipping_state` `shipping_state` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `shipping_city` `shipping_city` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `shipping_zip` `shipping_zip` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `subtotal` `subtotal` FLOAT NULL, CHANGE `tax` `tax` FLOAT NOT NULL DEFAULT '0', CHANGE `total` `total` FLOAT NULL DEFAULT '0', CHANGE `discount` `discount` FLOAT NULL DEFAULT '0', CHANGE `order_type` `order_type` INT(11) NULL, CHANGE `payment_method` `payment_method` INT(11) NULL, CHANGE `pos_user_id` `pos_user_id` INT(11) NULL, CHANGE `status` `status` INT(11) NULL, CHANGE `customer_id` `customer_id` INT(11) NULL, CHANGE `pos_pickup_status` `pos_pickup_status` INT(11) NULL;

ALTER TABLE `transactions` CHANGE `payment_type` `payment_type` INT(11) NULL, CHANGE `pos_order_id` `pos_order_id` INT(11) NULL, CHANGE `transaction_time` `transaction_time` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `pos_user_id` `pos_user_id` INT(11) NULL, CHANGE `customer_id` `customer_id` INT(11) NULL, CHANGE `tax` `tax` FLOAT NULL DEFAULT '0', CHANGE `discount` `discount` FLOAT NULL DEFAULT '0', CHANGE `subtotal` `subtotal` FLOAT NULL DEFAULT '0', CHANGE `total` `total` FLOAT NULL DEFAULT '0', CHANGE `status` `status` INT(11) NULL;

ALTER TABLE `customer` CHANGE `name` `name` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `email` `email` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `phone` `phone` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `password` `password` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `billing_zip` `billing_zip` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `billing_address` `billing_address` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `billing_country` `billing_country` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `billing_state` `billing_state` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `billing_city` `billing_city` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL, CHANGE `last_order` `last_order` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '0', CHANGE `num_orders` `num_orders` INT(11) NULL DEFAULT '0', CHANGE `status` `status` INT(11) NULL;

ALTER TABLE `pos_cart` CHANGE `secret_key` `secret_key` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL, CHANGE `expiry` `expiry` INT(11) NULL DEFAULT '0';


ALTER TABLE `inventory` CHANGE `free_ship` `free_ship` INT(11) NULL DEFAULT '2';

ALTER TABLE `inventory` CHANGE `video_url` `video_url` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;


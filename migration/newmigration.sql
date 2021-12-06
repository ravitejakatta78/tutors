ALTER TABLE `food_categeries` ADD `merchant_id` VARCHAR(100) NOT NULL AFTER `food_category`; 

CREATE TABLE food_category_types (
 `ID` INT NOT NULL AUTO_INCREMENT ,
 `food_cat_id` INT(11) NOT NULL ,
 `merchant_id` VARCHAR(100) NOT NULL ,
 `food_type_name` VARCHAR(255) NOT NULL ,
 `reg_date` VARCHAR(50) NOT NULL ,
 PRIMARY KEY (`ID`)) ENGINE = InnoDB; 
 
 ALTER TABLE `product` ADD `food_category_quantity` INT(11) NULL DEFAULT NULL AFTER `status`; 


ALTER TABLE `tablename` ADD `table_status` INT(11) NULL DEFAULT NULL AFTER `status`, ADD `current_order_id` INT(11) NULL DEFAULT NULL AFTER `table_status`; 

ALTER TABLE `merchant_gallery` ADD `status` INT NULL DEFAULT NULL AFTER `image`; 

CREATE TABLE `ingredients` ( `ID` INT(11) NOT NULL AUTO_INCREMENT , `item_name` VARCHAR(255) NOT NULL , `item_type` VARCHAR(255) NULL DEFAULT NULL , `item_price` DOUBLE NULL DEFAULT NULL , `photo` TEXT NULL DEFAULT NULL , `stock_alert` DOUBLE NULL DEFAULT NULL , `status` INT(11) NOT NULL , `reg_date` VARCHAR(50) NOT NULL , `modify_date` VARCHAR(50) NULL DEFAULT NULL , `merchant_id` VARCHAR(50) NOT NULL , PRIMARY KEY (`ID`)) ENGINE = InnoDB;

CREATE TABLE `merchant_recipe` ( `ID` INT NOT NULL AUTO_INCREMENT , `product_id` INT NOT NULL , `ingredient_id` INT NOT NULL , `ingred_quantity` DOUBLE NOT NULL , `ingred_price` DOUBLE NOT NULL , `status` INT NOT NULL , `reg_date` VARCHAR(50) NOT NULL , `modify_date` VARCHAR(50) NOT NULL , PRIMARY KEY (`ID`)) ENGINE = InnoDB; 
ALTER TABLE `merchant_recipe` ADD `merchant_id` INT NOT NULL AFTER `status`; 

CREATE TABLE `ingredient_purchase` ( `ID` INT NOT NULL AUTO_INCREMENT , `purchase_number` VARCHAR(100) NOT NULL , `merchant_id` VARCHAR(100) NOT NULL , `purchase_amount` DOUBLE NOT NULL , `status` INT NOT NULL , `reg_date` DATETIME NOT NULL , `modify_date` DATETIME NOT NULL , PRIMARY KEY (`ID`) ) ENGINE = InnoDB; 

CREATE TABLE `ingredient_purchase_detail` ( `ID` INT NOT NULL AUTO_INCREMENT , `purchase_id` INT NOT NULL , `purchase_quantity` DOUBLE NOT NULL , `purchase_price` DOUBLE NOT NULL , PRIMARY KEY (`ID`)) ENGINE = InnoDB; 

CREATE TABLE `sequence_master` (
 `ID` int(11) NOT NULL AUTO_INCREMENT,
 `seq_name` varchar(100) NOT NULL,
 `seq_number` int(11) NOT NULL,
 `reg_date` datetime NOT NULL,
 PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4

ALTER TABLE `sequence_master` ADD `merchant_id` INT(11) NOT NULL AFTER `seq_name`; 

ALTER TABLE `ingredient_purchase_detail` ADD `ingredient_id` INT NOT NULL AFTER `purchase_id`; 

ALTER TABLE `ingredient_purchase_detail` ADD `ingredient_name` varchar(100) NOT NULL AFTER `ingredient_id`; 

CREATE TABLE `ingredient_stock_register` (
 `ID` int(11) NOT NULL AUTO_INCREMENT,
 `merchant_id` varchar(100) NOT NULL,
 `ingredient_id` int(11) NOT NULL,
 `ingredient_name` varchar(255) NOT NULL,
 `opening_stock` double NOT NULL DEFAULT 0,
 `stock_in` double NOT NULL DEFAULT 0,
 `stock_out` double NOT NULL DEFAULT 0,
 `wastage` double NOT NULL DEFAULT 0,
 `closing_stock` double NOT NULL DEFAULT 0,
 `reg_date` datetime NOT NULL,
 PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4

ALTER TABLE `ingredient_stock_register` ADD `created_on` DATE NOT NULL AFTER `reg_date`; 


CREATE TABLE `merchant_vendor` ( `ID` INT(11) NOT NULL AUTO_INCREMENT 
, `store_name` VARCHAR(100) NOT NULL , `vendor_type` VARCHAR(100) NOT NULL 
, `owner_name` VARCHAR(100) NULL DEFAULT NULL , `owner_mobile` VARCHAR(20) NULL DEFAULT NULL 
, `manager_name` VARCHAR(100) NULL DEFAULT NULL , `manager_mobile` VARCHAR(20) NULL DEFAULT NULL 
, `vendor_location` VARCHAR(100) NOT NULL , `vendor_city` VARCHAR(100) NOT NULL 
, `vendor_range` VARCHAR(100) NOT NULL , `merchant_id` VARCHAR(20) NOT NULL 
, `status` INT NOT NULL 
, `created_by` VARCHAR(100) NOT NULL 
, `reg_date` DATETIME NOT NULL , PRIMARY KEY (`ID`)) ENGINE = InnoDB;

ALTER TABLE `ingredient_purchase_detail` ADD `purchase_qty_type` int(11) NOT NULL AFTER `purchase_quantity`; 

ALTER TABLE `ingredient_purchase` ADD `vendor_id` INT(11) NOT NULL AFTER `ID`, ADD `vendor_name` VARCHAR(255) NOT NULL AFTER `vendor_id`; 
ALTER TABLE `merchant_recipe` ADD `ingred_units` int not NULL AFTER `ingred_price` ;
CREATE TABLE `merchant_order_recipe_cost` ( `ID` INT NOT NULL AUTO_INCREMENT 
, `order_id` INT(11) NOT NULL , `product_id` INT(11) NOT NULL 
, `ingredi_id` INT(11) NOT NULL , `ingredi_qty` INT(11) NOT NULL 
, `ingredi_price` DOUBLE NOT NULL
 , `ingredi_name` VARCHAR(100) NOT NULL , PRIMARY KEY (`ID`)) ENGINE = InnoDB; 
ALTER TABLE `merchant_order_recipe_cost` ADD `reg_date` DATETIME NOT NULL AFTER `ingredi_name`; 
ALTER TABLE `merchant_order_recipe_cost` ADD `ingredi_detail_id` INT(11) NOT NULL AFTER `ingredi_name`; 
ALTER TABLE `merchant_order_recipe_cost` ADD `merchant_id` INT(11) NOT NULL AFTER `ID`; 

CREATE TABLE `merchant_employee` ( `ID` INT NOT NULL AUTO_INCREMENT , `merchant_id` INT NOT NULL , `emp_name` VARCHAR(100) NOT NULL , `emp_role` INT(11) NOT NULL , `emp_phone` VARCHAR(20) NOT NULL , `emp_exp` DOUBLE NOT NULL , `date_of_join` DATE NOT NULL , `emp_salary` DOUBLE NOT NULL , `emp_designation` VARCHAR(100) NOT NULL , `emp_specialities` INT NOT NULL , `emp_status` INT NOT NULL , `emp_id` INT NOT NULL , `reg_date` DATETIME NOT NULL , `mod_date` DATETIME NOT NULL , `created_by` VARCHAR(100) NOT NULL , PRIMARY KEY (`ID`)) ENGINE = InnoDB; 
ALTER TABLE `merchant_employee` ADD `emp_email` VARCHAR(100) NOT NULL AFTER `emp_phone`; 
ALTER TABLE `merchant_employee` CHANGE `emp_specialities` `emp_specialities` VARCHAR(255) NOT NULL; 
ALTER TABLE `merchant_employee` CHANGE `emp_id` `emp_id` VARCHAR(100) NOT NULL; 
CREATE TABLE `employee_role` ( `ID` INT NOT NULL AUTO_INCREMENT , `merchant_id` INT(11) NOT NULL , `role_name` VARCHAR(100) NOT NULL , `role_status` INT NOT NULL , `created_by` VARCHAR(100) NOT NULL , `reg_date` DATETIME NOT NULL , PRIMARY KEY (`ID`)) ENGINE = InnoDB; 
CREATE TABLE `employee_attendance` ( `ID` INT NOT NULL AUTO_INCREMENT , `merchant_id` INT NOT NULL , `employee_id` INT NOT NULL , `attendent_status` INT NOT NULL , `created_on` DATE NOT NULL , `reg_date` DATETIME NOT NULL , `created_by` VARCHAR(100) NOT NULL , PRIMARY KEY (`ID`)) ENGINE = InnoDB; 
ALTER TABLE `employee_attendance` ADD `mod_date` DATETIME NOT NULL AFTER `reg_date`; 
ALTER TABLE `merchant_employee` ADD `emp_password` TEXT NOT NULL AFTER `emp_name`; 
CREATE TABLE `merchant_permissions` ( `ID` INT NOT NULL , `process_name` VARCHAR(255) NOT NULL , `process_action` VARCHAR(255) NULL DEFAULT NULL , `process_status` INT NOT NULL , `reg_date` DATETIME NOT NULL ) ENGINE = InnoDB; 
ALTER TABLE `merchant_permissions` CHANGE `ID` `ID` INT(11) NOT NULL AUTO_INCREMENT, add PRIMARY KEY (`ID`); 
CREATE TABLE `merchant_permission_role_map` ( `ID` INT NOT NULL AUTO_INCREMENT , `merchant_id` INT NOT NULL , `employee_id` INT NOT NULL , `permission_id` INT NOT NULL , `permission_status` INT NOT NULL , PRIMARY KEY (`ID`)) ENGINE = InnoDB; 
INSERT INTO `merchant_permissions` (`ID`, `process_name`, `process_action`, `process_status`, `reg_date`) VALUES (NULL, 'KDS', '/merchant/viewkds', '1', '2020-05-06 18:46:21'); 
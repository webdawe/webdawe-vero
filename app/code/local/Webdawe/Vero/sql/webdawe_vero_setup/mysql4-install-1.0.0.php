<?php
$installer = $this;
$installer->startSetup();




//Vero Customer Queue
$query=<<<SQLTEXT
CREATE TABLE `webdawe_vero_customer` (
  `queue_id` int(11) NOT NULL AUTO_INCREMENT,
  `vero_id` varchar(100) DEFAULT NULL,
  `customer_id` int(11) NOT NULL,
  `subscriber_id` int(11) DEFAULT NULL,
  `store_id` smallint(5) unsigned DEFAULT NULL,
  `website_id` smallint(5) unsigned DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `is_subscriber` smallint(5) unsigned DEFAULT NULL,
  `is_guest` smallint(5) unsigned DEFAULT NULL,
  `is_imported` smallint(5) unsigned DEFAULT NULL,
  `priority` smallint(5) unsigned DEFAULT NULL,
  `attempts` smallint(5) DEFAULT '0',
  `action` smallint(5) unsigned DEFAULT NULL,
  `message` TEXT NULL,
  `tags` text NULL,
  `created_at` datetime DEFAULT NULL,
  `modified_at` datetime DEFAULT NULL,
  PRIMARY KEY (`queue_id`),
  KEY `webdawe_vero_customer_customer_id` (`customer_id`),
  KEY `webdawe_vero_customer_subscriber_id` (`subscriber_id`),
  KEY `webdawe_vero_customer_email` (`email`),
  KEY `webdawe_vero_customer_store_id` (`store_id`),
  KEY `webdawe_vero_customer_website_id` (`website_id`),
  KEY `webdawe_vero_customer_priority` (`priority`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Vero Customer';


SQLTEXT;

$installer->run($query);

//Vero Customer Import History
$query=<<<SQLTEXT
CREATE TABLE `webdawe_vero_customer_import_history` (
    `history_id` INT(11) NOT NULL AUTO_INCREMENT,
    `message` TEXT NULL,
    `status` SMALLINT(5) UNSIGNED NULL,
    `created_at` DATETIME NULL,
    `modified_at` DATETIME NULL,
    PRIMARY KEY(`history_id`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Vero Customer Import History';

SQLTEXT;

$installer->run($query);

//Vero Quote Queue
$query=<<<SQLTEXT
 CREATE TABLE `webdawe_vero_quote_queue` (
  `queue_id` int(11) NOT NULL AUTO_INCREMENT,
  `quote_id` int(11) DEFAULT NULL,
  `vero_id` varchar(100) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `is_imported` smallint(11) unsigned DEFAULT NULL,
  `priority` smallint(5) unsigned DEFAULT NULL,
  `attempts` smallint(5) DEFAULT '0',
  `message` TEXT NULL,
  `created_at` datetime DEFAULT NULL,
  `modified_at` datetime DEFAULT NULL,
  PRIMARY KEY (`queue_id`),
  UNIQUE KEY `webdawe_vero_quote_queue_quote_id` (`quote_id`),
  KEY `webdawe_vero_quote_queue_vero_id` (`vero_id`),
  KEY `webdawe_vero_quote_queue_email` (`email`),
  KEY `webdawe_vero_quote_queue_priority` (`priority`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Vero Quote Queue';

SQLTEXT;

$installer->run($query);

//Vero Quote Import History
$query=<<<SQLTEXT
CREATE TABLE `webdawe_vero_quote_import_history` (
    `history_id` INT(11) NOT NULL AUTO_INCREMENT,
    `email` int(11) DEFAULT NULL,
    `message` TEXT NULL,
    `status` SMALLINT(5) UNSIGNED NULL,
    `created_at` DATETIME NULL,
    `modified_at` DATETIME NULL,
    PRIMARY KEY(`history_id`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Vero Quote Import History';

SQLTEXT;

$installer->run($query);

//Vero Order Queue
$query=<<<SQLTEXT
  CREATE TABLE `webdawe_vero_order_queue` (
  `queue_id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) DEFAULT NULL,
  `quote_id` int(11) DEFAULT NULL,
  `vero_id` varchar(100) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `is_imported` smallint(11) unsigned DEFAULT NULL,
  `priority` smallint(5) unsigned DEFAULT NULL,
  `attempts` smallint(5) DEFAULT '0',
  `message` TEXT NULL,
  `created_at` datetime DEFAULT NULL,
  `modified_at` datetime DEFAULT NULL,
  PRIMARY KEY (`queue_id`),
  UNIQUE KEY `webdawe_vero_order_queue_order_id` (`order_id`),
  KEY `webdawe_vero_order_queue_vero_id` (`vero_id`),
  KEY `webdawe_vero_order_queue_quote_id` (`quote_id`),
  KEY `webdawe_vero_order_queue_email` (`email`),
  KEY `webdawe_vero_order_queue_priority` (`priority`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Vero Quote Queue';

SQLTEXT;

$installer->run($query);

//Vero Order Import History
$query=<<<SQLTEXT
CREATE TABLE `webdawe_vero_order_import_history` (
    `history_id` INT(11) NOT NULL AUTO_INCREMENT,
    `message` TEXT NULL,
    `status` SMALLINT(5) UNSIGNED NULL,
    `created_at` DATETIME NULL,
    `modified_at` DATETIME NULL,
    PRIMARY KEY(`history_id`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Vero Order Import History';

SQLTEXT;

$installer->run($query);

$installer->endSetup();
	 
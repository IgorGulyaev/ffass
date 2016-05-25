<?php

$installer = $this;

$installer->startSetup();
$table = $this->getTable('facebookfree/facebookfree');

$installer->run("

CREATE TABLE IF NOT EXISTS `{$table}` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `customer_id` int(10) unsigned NOT NULL,
  `store_id` smallint(5) unsigned NOT NULL,
  `website_id` smallint(5) unsigned NOT NULL,
  `fb_id` bigint(20) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `customer_id` (`customer_id`),
  UNIQUE KEY `store_id` (`store_id`,`website_id`,`fb_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


");

$installer->endSetup();

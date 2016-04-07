<?php
 /**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * @category    Softprodigy
 * @package     Softprodigy_Giftwrap
 * @copyright   Copyright (c) 2013 Softprodigy System Solutions Pvt. Ltd (http://www.softprodigy.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
 
$installer = $this;
/* @var $installer Mage_Core_Model_Resource_Setup */

$installer->startSetup();
$installer->run("
CREATE TABLE IF NOT EXISTS `{$this->getTable('giftwrap_entity')}` (
  `entity_id` int(10) unsigned NOT NULL auto_increment,
  `entity_type_id` smallint(8) unsigned NOT NULL default '0',
  `attribute_set_id` smallint(5) unsigned NOT NULL default '0',
  `website_id` smallint(5) unsigned default NULL,
  `email` varchar(255) NOT NULL default '',
  `group_id` smallint(3) unsigned NOT NULL default '0',
  `increment_id` varchar(50) NOT NULL default '', `parent_id` int(10) unsigned NOT NULL default '0',
  `store_id` smallint(5) unsigned default '0',
  `created_at` datetime NOT NULL default '0000-00-00 00:00:00',
  `updated_at` datetime NOT NULL default '0000-00-00 00:00:00',
  `is_active` tinyint(1) unsigned NOT NULL default '1',
  PRIMARY KEY  (`entity_id`),
  KEY `FK_giftwrap_ENTITY_STORE` (`store_id`),
  KEY `IDX_ENTITY_TYPE` (`entity_type_id`),
  KEY `IDX_AUTH` (`email`,`website_id`),
  KEY `FK_giftwrap_WEBSITE` (`website_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='Customer Entityies' AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `{$this->getTable('giftwrap_entity_datetime')}` (
  `value_id` int(11) NOT NULL auto_increment,
  `entity_type_id` smallint(8) unsigned NOT NULL default '0',
  `attribute_id` smallint(5) unsigned NOT NULL default '0',
  `entity_id` int(10) unsigned NOT NULL default '0',
  `value` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`value_id`),
  KEY `FK_GIFTWRAP_DATETIME_ENTITY_TYPE` (`entity_type_id`),
  KEY `FK_GIFTWRAP_DATETIME_ATTRIBUTE` (`attribute_id`),
  KEY `FK_GIFTWRAP_DATETIME_ENTITY` (`entity_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `{$this->getTable('giftwrap_entity_decimal')}` (
  `value_id` int(11) NOT NULL auto_increment,
  `entity_type_id` smallint(8) unsigned NOT NULL default '0',
  `attribute_id` smallint(5) unsigned NOT NULL default '0',
  `entity_id` int(10) unsigned NOT NULL default '0',
  `value` decimal(12,4) NOT NULL default '0.0000',
  PRIMARY KEY  (`value_id`),
  KEY `FK_GIFTWRAP_DECIMAL_ENTITY_TYPE` (`entity_type_id`),
  KEY `FK_GIFTWRAP_DECIMAL_ATTRIBUTE` (`attribute_id`),
  KEY `FK_GIFTWRAP_DECIMAL_ENTITY` (`entity_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `{$this->getTable('giftwrap_entity_int')}` (
  `value_id` int(11) NOT NULL auto_increment,
  `entity_type_id` smallint(8) unsigned NOT NULL default '0',
  `attribute_id` smallint(5) unsigned NOT NULL default '0',
  `entity_id` int(10) unsigned NOT NULL default '0',
  `value` int(11) NOT NULL default '0',
  PRIMARY KEY  (`value_id`),
  KEY `FK_GIFTWRAP_INT_ENTITY_TYPE` (`entity_type_id`),
  KEY `FK_GIFTWRAP_INT_ATTRIBUTE` (`attribute_id`),
  KEY `FK_GIFTWRAP_INT_ENTITY` (`entity_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `{$this->getTable('giftwrap_entity_text')}` (
  `value_id` int(11) NOT NULL auto_increment,
  `entity_type_id` smallint(8) unsigned NOT NULL default '0',
  `attribute_id` smallint(5) unsigned NOT NULL default '0',
  `entity_id` int(10) unsigned NOT NULL default '0',
  `value` text NOT NULL,
  PRIMARY KEY  (`value_id`),
  KEY `FK_GIFTWRAP_TEXT_ENTITY_TYPE` (`entity_type_id`),
  KEY `FK_GIFTWRAP_TEXT_ATTRIBUTE` (`attribute_id`),
  KEY `FK_GIFTWRAP_TEXT_ENTITY` (`entity_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `{$this->getTable('giftwrap_entity_varchar')}` (
  `value_id` int(11) NOT NULL auto_increment,
  `entity_type_id` smallint(8) unsigned NOT NULL default '0',
  `attribute_id` smallint(5) unsigned NOT NULL default '0',
  `entity_id` int(10) unsigned NOT NULL default '0',
  `value` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`value_id`),
  KEY `FK_GIFTWRAP_VARCHAR_ENTITY_TYPE` (`entity_type_id`),
  KEY `FK_GIFTWRAP_VARCHAR_ATTRIBUTE` (`attribute_id`),
  KEY `FK_GIFTWRAP_VARCHAR_ENTITY` (`entity_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


ALTER TABLE `{$this->getTable('giftwrap_entity')}`
  ADD CONSTRAINT `FK_GIFTWRAP_ENTITY_STORE` FOREIGN KEY (`store_id`) REFERENCES `{$this->getTable('core_store')}` (`store_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_GIFTWRAP_WEBSITE` FOREIGN KEY (`website_id`) REFERENCES `{$this->getTable('core_website')}` (`website_id`) ON DELETE SET NULL ON UPDATE CASCADE;


ALTER TABLE `{$this->getTable('giftwrap_entity_datetime')}`
  ADD CONSTRAINT `FK_GIFTWRAP_DATETIME_ATTRIBUTE` FOREIGN KEY (`attribute_id`) REFERENCES `{$this->getTable('eav_attribute')}` (`attribute_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_GIFTWRAP_DATETIME_ENTITY` FOREIGN KEY (`entity_id`) REFERENCES `{$this->getTable('giftwrap_entity')}` (`entity_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_GIFTWRAP_DATETIME_ENTITY_TYPE` FOREIGN KEY (`entity_type_id`) REFERENCES `{$this->getTable('eav_entity_type')}` (`entity_type_id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `{$this->getTable('giftwrap_entity_decimal')}`
  ADD CONSTRAINT `FK_GIFTWRAP_DECIMAL_ATTRIBUTE` FOREIGN KEY (`attribute_id`) REFERENCES `{$this->getTable('eav_attribute')}` (`attribute_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_GIFTWRAP_DECIMAL_ENTITY` FOREIGN KEY (`entity_id`) REFERENCES `{$this->getTable('giftwrap_entity')}` (`entity_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_GIFTWRAP_DECIMAL_ENTITY_TYPE` FOREIGN KEY (`entity_type_id`) REFERENCES `{$this->getTable('eav_entity_type')}` (`entity_type_id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `{$this->getTable('giftwrap_entity_int')}`
  ADD CONSTRAINT `FK_GIFTWRAP_INT_ATTRIBUTE` FOREIGN KEY (`attribute_id`) REFERENCES `{$this->getTable('eav_attribute')}` (`attribute_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_GIFTWRAP_INT_ENTITY` FOREIGN KEY (`entity_id`) REFERENCES `{$this->getTable('giftwrap_entity')}` (`entity_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_GIFTWRAP_INT_ENTITY_TYPE` FOREIGN KEY (`entity_type_id`) REFERENCES `{$this->getTable('eav_entity_type')}` (`entity_type_id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `{$this->getTable('giftwrap_entity_text')}`
  ADD CONSTRAINT `FK_GIFTWRAP_TEXT_ATTRIBUTE` FOREIGN KEY (`attribute_id`) REFERENCES `{$this->getTable('eav_attribute')}` (`attribute_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_GIFTWRAP_TEXT_ENTITY` FOREIGN KEY (`entity_id`) REFERENCES `{$this->getTable('giftwrap_entity')}` (`entity_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_GIFTWRAP_TEXT_ENTITY_TYPE` FOREIGN KEY (`entity_type_id`) REFERENCES `{$this->getTable('eav_entity_type')}` (`entity_type_id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `{$this->getTable('giftwrap_entity_varchar')}`
  ADD CONSTRAINT `FK_GIFTWRAP_VARCHAR_ATTRIBUTE` FOREIGN KEY (`attribute_id`) REFERENCES `{$this->getTable('eav_attribute')}` (`attribute_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_GIFTWRAP_VARCHAR_ENTITY` FOREIGN KEY (`entity_id`) REFERENCES `{$this->getTable('giftwrap_entity')}` (`entity_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_GIFTWRAP_VARCHAR_ENTITY_TYPE` FOREIGN KEY (`entity_type_id`) REFERENCES `{$this->getTable('eav_entity_type')}` (`entity_type_id`) ON DELETE CASCADE ON UPDATE CASCADE;
	
ALTER TABLE  `".$this->getTable('sales/order')."` ADD  `giftwrap_amount` DECIMAL( 10, 2 ) NOT NULL;
ALTER TABLE  `".$this->getTable('sales/order')."` ADD  `base_giftwrap_amount` DECIMAL( 10, 2 ) NOT NULL;

ALTER TABLE  `".$this->getTable('sales/quote_address')."` ADD  `giftwrap_amount` DECIMAL( 10, 2 ) NOT NULL;
ALTER TABLE  `".$this->getTable('sales/quote_address')."` ADD  `base_giftwrap_amount` DECIMAL( 10, 2 ) NOT NULL;

ALTER TABLE  `".$this->getTable('sales/order')."` ADD  `giftwrap_amount_invoiced` DECIMAL( 10, 2 ) NOT NULL;
ALTER TABLE  `".$this->getTable('sales/order')."` ADD  `base_giftwrap_amount_invoiced` DECIMAL( 10, 2 ) NOT NULL;

ALTER TABLE  `".$this->getTable('sales/invoice')."` ADD  `giftwrap_amount` DECIMAL( 10, 2 ) NOT NULL;
ALTER TABLE  `".$this->getTable('sales/invoice')."` ADD  `base_giftwrap_amount` DECIMAL( 10, 2 ) NOT NULL;

ALTER TABLE  `".$this->getTable('sales/order')."` ADD  `giftwrap_amount_refunded` DECIMAL( 10, 2 ) NOT NULL;
ALTER TABLE  `".$this->getTable('sales/order')."` ADD  `base_giftwrap_amount_refunded` DECIMAL( 10, 2 ) NOT NULL;
	
ALTER TABLE  `".$this->getTable('sales/creditmemo')."` ADD  `giftwrap_amount` DECIMAL( 10, 2 ) NOT NULL;
ALTER TABLE  `".$this->getTable('sales/creditmemo')."` ADD  `base_giftwrap_amount` DECIMAL( 10, 2 ) NOT NULL;	
	");

$installer->installEntities();

$installer->endSetup();

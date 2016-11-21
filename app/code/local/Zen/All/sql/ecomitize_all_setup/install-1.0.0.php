<?php

/* @var $installer Mage_Catalog_Model_Resource_Setup */
$installer = new Mage_Catalog_Model_Resource_Setup('core_write');

$installer->startSetup();

$installer->addAttribute('catalog_product', 'featured', array(
    'group'             => 'General',
    'type'              => 'int',
    'backend'           => '',
    'frontend'          => '',
    'label'             => 'Featured',
    'input'             => 'select',
    'class'             => '',
    'source'            => 'eav/entity_attribute_source_boolean',
    'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
    'visible'           => true,
    'required'          => false,
    'user_defined'      => true,
    'default'           => '',
    'searchable'        => true,
    'filterable'        => true,
    'comparable'        => true,
    'visible_on_front'  => true,
    'unique'            => false,
    'is_configurable'   => false,
    'visible_in_advanced_search'    => true,
    'used_in_product_listing'       => true,
    'used_for_sort_by'  => true,
    'sort_order'        => 101
));

$installer->endSetup();
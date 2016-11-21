<?php
/**
 * Created by PhpStorm.
 * User: Vladimir Prutyan
 * Date: 4/26/2016
 * Time: 12:39 PM
 */

$attributeGroup = 'General';          // Group to add the attribute to
$attributeSetId = 4;          // Array with attribute set ID's to add this attribute to. (ID:4 is the Default Attribute Set)

// Configuration:
$data_true = array(
    'type'      => 'int',       // Attribute type
    'input'     => 'select',          // Input type
    'source'    => 'eav/entity_attribute_source_boolean',
    'global'    => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,    // Attribute scope
    'required'  => false,
    'user_defined' => false,
    'searchable' => false,
    'filterable' => false,
    'comparable' => false,
    'visible_on_front' => true,
    'unique' => false,
    'used_in_product_listing' => true,
    'label' => 'Fits True to Size'
);

// Configuration:
$data_small = array(
    'type'      => 'int',       // Attribute type
    'input'     => 'select',          // Input type
    'source'    => 'eav/entity_attribute_source_boolean',
    'global'    => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,    // Attribute scope
    'required'  => false,
    'user_defined' => false,
    'searchable' => false,
    'filterable' => false,
    'comparable' => false,
    'visible_on_front' => true,
    'unique' => false,
    'used_in_product_listing' => true,
    'label' => 'Runs Small'
);

// Configuration:
$data_big = array(
    'type'      => 'int',       // Attribute type
    'input'     => 'select',          // Input type
    'source'    => 'eav/entity_attribute_source_boolean',
    'global'    => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,    // Attribute scope
    'required'  => false,
    'user_defined' => false,
    'searchable' => false,
    'filterable' => false,
    'comparable' => false,
    'visible_on_front' => true,
    'unique' => false,
    'used_in_product_listing' => true,
    'label' => 'Runs Big'
);

$installer = Mage::getResourceModel('catalog/setup', 'catalog_setup');
$installer->startSetup();
$installer->addAttribute('catalog_product', 'fits_true', $data_true);
$installer->addAttribute('catalog_product', 'runs_small', $data_small);
$installer->addAttribute('catalog_product', 'runs_big', $data_big);
$installer->addAttributeToGroup('catalog_product', $attributeSetId, $attributeGroup, 'fits_true');
$installer->addAttributeToGroup('catalog_product', $attributeSetId, $attributeGroup, 'runs_small');
$installer->addAttributeToGroup('catalog_product', $attributeSetId, $attributeGroup, 'runs_big');

$installer->endSetup();

$productIds = Mage::getResourceModel('catalog/product_collection')
    ->getAllIds();

// Now create an array of attribute_code => values
$attributeData = array("fits_true" => "1");

// Set the store to affect. I used admin to change all default values
$storeId = 0;

// Now update the attribute for the given products.
Mage::getSingleton('catalog/product_action')
    ->updateAttributes($productIds, $attributeData, $storeId);
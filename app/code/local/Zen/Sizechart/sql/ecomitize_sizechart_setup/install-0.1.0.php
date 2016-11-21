<?php
/**
 * Created by PhpStorm.
 * User: Vladimir Prutyan
 * Date: 4/26/2016
 * Time: 12:39 PM
 */

$attributeName  = 'Size Chart'; // Name of the attribute
$attributeCode  = 'size_chart'; // Code of the attribute
$attributeGroup = 'General';          // Group to add the attribute to
$attributeSetId = 4;          // Array with attribute set ID's to add this attribute to. (ID:4 is the Default Attribute Set)

// Configuration:
$data = array(
    'type'      => 'varchar',       // Attribute type
    'input'     => 'text',          // Input type
    'global'    => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,    // Attribute scope
    'required'  => false,
    'user_defined' => false,
    'searchable' => false,
    'filterable' => false,
    'comparable' => false,
    'visible_on_front' => true,
    'unique' => false,
    'used_in_product_listing' => true,
    'label' => $attributeName
);

$installer = Mage::getResourceModel('catalog/setup', 'catalog_setup');
$installer->startSetup();
$installer->addAttribute('catalog_product', $attributeCode, $data);
$installer->addAttributeToGroup('catalog_product', $attributeSetId, $attributeGroup, $attributeCode);

$installer->endSetup();
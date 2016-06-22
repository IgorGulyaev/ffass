<?php
/**
 * Created by PhpStorm.
 * User: Vladimir Prutyan
 * Date: 6/21/2016
 * Time: 5:45 PM
 */

$this->startSetup();
$this->addAttribute(Mage_Catalog_Model_Category::ENTITY, 'category_short_description', array(
    'group'         => 'General Information',
    'input'         => 'textarea',
    'type'          => 'varchar',
    'label'         => 'Short Description',
    'backend'       => '',
    'visible'       => true,
    'required'      => false,
    'visible_on_front' => true,
    'default'       => '',
    'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
    
));

$this->endSetup();
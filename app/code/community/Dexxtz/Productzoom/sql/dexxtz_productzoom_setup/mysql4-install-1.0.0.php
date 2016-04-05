<?php

/**
 * Copyright [2015] [Dexxtz]
 *
 * @package   Dexxtz_Productzoom
 * @author    Dexxtz
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 */

$installer = $this;
$setup = new Mage_Eav_Model_Entity_Setup('core_setup');
$installer->startSetup(); 

$setup->addAttributeGroup('catalog_product', 'Default', 'Video', 4);

$setup->addAttribute('catalog_product', 'dexxtz_video', array(
    'group' => 'Video',
    'input' => 'textarea',
    'type'  => 'text',
    'label' => 'Video (embed)',
    'default'  => '',
    'class'    => '',
    'backend'  => '',
    'frontend' => '',
    'source'   => '',
    'global'   => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
    'visible'  => true,
    'required' => false,
    'user_defined' => false,
    'searchable'   => false,
    'filterable'   => false,
    'comparable'   => false,
    'visible_on_front' => true,
    'visible_in_advanced_search' => false,
    'unique' => false
));
 
$installer->endSetup();
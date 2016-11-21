<?php
/**
 * Created by PhpStorm.
 * User: Vladimir Prutyan
 * Date: 29.05.2016
 * Time: 14:05
 */

$this->startSetup();
Mage::register('isSecureArea', 1);

// Force the store to be admin
Mage::app()->setUpdateMode(false);
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

$categoryFeatured = Mage::getModel('catalog/category');
$categoryFeatured->setPath('1/2') // set parent to be root category
->setStoreId(Mage_Core_Model_App::ADMIN_STORE_ID)
    ->setName('New Arrivals')
    ->setUrlKey('onsale')
    ->setIncludeInMenu(0)
    ->setIsActive(1)
    ->setDisplayMode('PRODUCTS')
    ->setIsAnchor(1) //for active anchor
    ->save();

$categoryOnsale = Mage::getModel('catalog/category');
$categoryOnsale->setPath('1/2') // set parent to be root category
->setStoreId(Mage_Core_Model_App::ADMIN_STORE_ID)
    ->setName('Customer`s Favorites')
    ->setUrlKey('featured')
    ->setIncludeInMenu(0)
    ->setIsActive(1)
    ->setDisplayMode('PRODUCTS')
    ->setIsAnchor(1) //for active anchor
    ->save();

$categoryClearance = Mage::getModel('catalog/category');
$categoryClearance->setPath('1/2') // set parent to be root category
->setStoreId(Mage_Core_Model_App::ADMIN_STORE_ID)
    ->setName('Clearance')
    ->setUrlKey('clearance')
    ->setIncludeInMenu(0)
    ->setIsActive(1)
    ->setDisplayMode('PRODUCTS')
    ->setIsAnchor(1) //for active anchor
    ->save();
$this->endSetup();

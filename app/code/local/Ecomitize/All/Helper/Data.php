<?php
/**
 * Created by PhpStorm.
 * User: Vladimir Prutyan
 * Date: 12/21/2015
 * Time: 4:27 PM
 */

class Ecomitize_All_Helper_data extends Mage_Core_Helper_Abstract {

    public function getManufacturers(){

        $options = array();
        $attribute = Mage::getSingleton('eav/config')
            ->getAttribute(Mage_Catalog_Model_Product::ENTITY, 'manufacturer');

        if ($attribute->usesSource()) {
            $options = $attribute->getSource()->getAllOptions(false);
        }

        return $options;
    }

    public function getBrands(){

        $options = array();
        $attribute = Mage::getSingleton('eav/config')
            ->getAttribute(Mage_Catalog_Model_Product::ENTITY, 'brand');

        if ($attribute->usesSource()) {
            $options = $attribute->getSource()->getAllOptions(false);
        }
        return $options;
    }

    public function getConfigurableOptionsPriceRange($_product){

        if($_product->getTypeId() == 'simple'){
            return $_product->getFinalPrice();
        }

        if($_product->getTypeId() == 'configurable') {

            $block = Mage::app()->getLayout()->createBlock('catalog/product_view_type_configurable');
            $block->setProduct($_product);
            $_products = $block->getAllowProducts();
            $config = json_decode($block->getJsonConfig(), true);

            $basePrice = $_product->getSpecialPrice();
            if (is_null($basePrice)) {
                $basePrice = $_product->getFinalPrice();
            }

            foreach ($_products as $_allowProduct) {
                $option_price = 0;
                foreach ($config['attributes'] as $aId => $aValues) {
                    foreach ($aValues['options'] as $key => $value) {
                        if ($value['label'] === $_allowProduct->getAttributeText($aValues['code'])) {
                            $option_price += $value['price'];
                        }
                    }
                }
                $priceRange[] = $option_price + $basePrice;
            }

            return $priceRange;
        }
    }
}
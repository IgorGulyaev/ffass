<?php

    class Bc_Brand_Helper_Data extends Mage_Core_Helper_Abstract
    {
        public function getBrandUrl(){
            return $this->_getUrl('brand/index');
        }
}
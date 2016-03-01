<?php
class Bc_Brand_Block_MenuBrand extends Mage_Core_Block_Template
{

    public function _prepareLayout()
    {
        return parent::_prepareLayout();
    }

    public function getBrand()
    {
        if (!$this->hasData('brand')) {
            $this->setData('brand', Mage::registry('brand'));
        }
        return $this->getData('brand');

    }

    public function setBrand($brandToDisplay = array()){
        $this->setData('brand_to_display',$brandToDisplay);
    }
}
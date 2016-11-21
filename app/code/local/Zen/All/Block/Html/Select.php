<?php
class Zen_All_Block_Html_Select extends Mage_Core_Block_Html_Select
{
    /**
     * Alias for toHtml()
     *
     * @return string
     */
    public function getHtml()
    {
        if ($this->getName() == 'country_id') {
            $this->setClass('input-text ' . $this->getClass());
        }
        if ($this->getName() == 'billing[country_id]') {
            $this->setClass('input-text ' . $this->getClass());
        }
        if ($this->getName() == 'billing_address_id') {
            $this->setClass('input-text ' . $this->getClass());
        }
        return $this->toHtml();
    }
}
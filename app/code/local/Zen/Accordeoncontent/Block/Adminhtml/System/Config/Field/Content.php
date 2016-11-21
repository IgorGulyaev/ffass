<?php

class Zen_Accordeoncontent_Block_Adminhtml_System_Config_Field_Content extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract //Mage_Core_Block_Html_Select
{

    public function _getInputValueElement(Varien_Object $row)
    {
        return  '<input type="textarea" class="input-text '
        . $this->getColumn()->getValidateClass()
        . '" name="' . $this->getColumn()->getId()
        . '" value="' . $this->_getInputValue($row) . '"/>';
    }

}
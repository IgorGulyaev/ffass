<?php

class Zen_Accordeoncontent_Block_Adminhtml_System_Config_Taboptions extends Zen_Accordeoncontent_Block_System_Config_Form_Field_Array_Abstract //Mage_Adminhtml_Block_System_Config_Form_Field_Array_Abstract
    implements Varien_Data_Form_Element_Renderer_Interface
{
    protected $_tabRenderer;
    protected $_typeRenderer;

    public function _prepareToRender()
    {
        $this->addColumn('title', array(
            'label' => Mage::helper('zen_accordeoncontent')->__('Page title'),
        ));

        $this->addColumn('content', array(
            'label' => Mage::helper('zen_accordeoncontent')->__('Content'),
            'type' => 'textarea',
            'class' => 'textarea',
            'attributes' => 'rows="10" cols="45"',
            'style' => 'width:250px; height: 30Px;',
        ));

        $this->addColumn('type', array(
            'label' => Mage::helper('zen_accordeoncontent')->__('Type'),
            'renderer' => $this->_getTypeRenderer(),

        ));

        $this->addColumn('tab_sort', array(
            'label' => Mage::helper('zen_accordeoncontent')->__('Sort'),

        ));

        $this->_addAfter = false;
        $this->_addButtonLabel = Mage::helper('zen_accordeoncontent')->__('Add New Item');
    }

    protected function _getTabRenderer()
    {
        if (!$this->_tabRenderer) {
            $this->_tabRenderer = $this->getLayout()->createBlock(
                'zen_accordeoncontent/adminhtml_system_config_field_tab', '',
                array('is_render_to_js_template' => true)
            );
        }
        return $this->_tabRenderer;
    }

    protected function _getTypeRenderer()
    {
        if (!$this->_typeRenderer) {
            $this->_typeRenderer = $this->getLayout()->createBlock(
                'zen_accordeoncontent/adminhtml_system_config_field_type', '',
                array('is_render_to_js_template' => true)
            );
        }
        return $this->_typeRenderer;
    }

    protected function _getContentRenderer()
    {
        if (!$this->_getContentRenderer) {
            $this->_getContentRenderer = $this->getLayout()->createBlock(
                'zen_accordeoncontent/adminhtml_system_config_field_content', '',
                array('is_render_to_js_template' => true)
            );
        }
        return $this->_getContentRenderer;
    }

    protected function _prepareArrayRow(Varien_Object $row)
    {
        $row->setData(
            'option_extra_attr_' . $this->_getTabRenderer()
                ->calcOptionHash($row->getData('title')),
            'selected="selected"'
        );


        $row->setData(
            'option_extra_attr_' . $this->_getTypeRenderer()
                ->calcOptionHash($row->getData('type')),
            'selected="selected"'
        );
    }
}

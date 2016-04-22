<?php

class Ecomitize_Pagesaccordeon_Block_Adminhtml_System_Config_Taboptions extends Mage_Adminhtml_Block_System_Config_Form_Field_Array_Abstract
    implements Varien_Data_Form_Element_Renderer_Interface
{
    protected $_tabRenderer;
    protected $_typeRenderer;
    protected $_linkConfRenderer;

    public function _prepareToRender()
    {
        $this->addColumn('attribute', array(
            'label' => Mage::helper('ecomitize_pagesaccordeon')->__('Page'),
            'renderer' => $this->_getTabRenderer(),
        ));

        $this->addColumn('custompagename', array(
            'label' => Mage::helper('ecomitize_pagesaccordeon')->__('Custom page name'),
        ));

        $this->addColumn('link', array(
            'label' => Mage::helper('ecomitize_pagesaccordeon')->__('Link'),
        ));

        $this->addColumn('linktype', array(
            'label' => Mage::helper('ecomitize_pagesaccordeon')->__('Link type'),
            'renderer' => $this->_getLinkConfRenderer(),

        ));

        $this->addColumn('type', array(
            'label' => Mage::helper('ecomitize_pagesaccordeon')->__('Type'),
            'renderer' => $this->_getTypeRenderer(),

        ));

        $this->addColumn('tab_sort', array(
            'label' => Mage::helper('ecomitize_pagesaccordeon')->__('Sort'),

        ));

        $this->_addAfter = false;
        $this->_addButtonLabel = Mage::helper('ecomitize_pagesaccordeon')->__('Add New Item');
    }

    protected function  _getTabRenderer()
    {
        if (!$this->_tabRenderer) {
            $this->_tabRenderer = $this->getLayout()->createBlock(
                'ecomitize_pagesaccordeon/adminhtml_system_config_field_tab', '',
                array('is_render_to_js_template' => true)
            );
        }
        return $this->_tabRenderer;
    }

    protected function  _getTypeRenderer()
    {
        if (!$this->_typeRenderer) {
            $this->_typeRenderer = $this->getLayout()->createBlock(
                'ecomitize_pagesaccordeon/adminhtml_system_config_field_type', '',
                array('is_render_to_js_template' => true)
            );
        }
        return $this->_typeRenderer;
    }
    protected function _getLinkConfRenderer(){
        if (!$this->_linkConfRenderer) {
            $this->_linkConfRenderer = $this->getLayout()->createBlock(
                'ecomitize_pagesaccordeon/adminhtml_system_config_field_linkconfig', '',
                array('is_render_to_js_template' => true)
            );
        }
        return $this->_linkConfRenderer;
    }

    protected function _prepareArrayRow(Varien_Object $row)
    {
        $row->setData(
            'option_extra_attr_' . $this->_getTabRenderer()
                ->calcOptionHash($row->getData('attribute')),
            'selected="selected"'
        );

        $row->setData(
            'option_extra_attr_' . $this->_getLinkConfRenderer()
                ->calcOptionHash($row->getData('linktype')),
            'selected="selected"'
        );

        $row->setData(
            'option_extra_attr_' . $this->_getTypeRenderer()
                ->calcOptionHash($row->getData('type')),
            'selected="selected"'
        );
    }
}

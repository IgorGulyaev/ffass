<?php

class Ecomitize_Accordeoncontent_Model_Widget_Widget_Instance extends Mage_Widget_Model_Widget_Instance {

    public function generateLayoutUpdateXml($blockReference, $templatePath = '')
    {
        $templateFilename = Mage::getSingleton('core/design_package')->getTemplateFilename($templatePath, array(
            '_area'    => $this->getArea(),
            '_package' => $this->getPackage(),
            '_theme'   => $this->getTheme()
        ));
        if (!$this->getId() && !$this->isCompleteToCreate()
            || ($templatePath && !is_readable($templateFilename)))
        {
            return '';
        }
        $parameters = $this->getWidgetParameters();
        $xml = '<reference name="' . $blockReference . '">';
        $template = '';
        if (isset($parameters['template'])) {
            unset($parameters['template']);
        }
        if ($templatePath) {
            $template = ' template="' . $templatePath . '"';
        }

        $hash = Mage::helper('core')->uniqHash();
        $xml .= '<block type="' . $this->getType() . '" name="' . $hash . '"' . $template . '>';
        foreach ($parameters as $name => $value) {
            if ( $this->isMultiArray($value) ) {
                $value = serialize($value);
            } else if (is_array($value)) {
                $value = implode(',', $value);
            }
            if ($name && strlen((string)$value)) {
                $xml .= '<action method="setData">'
                    . '<name>' . $name . '</name>'
                    . '<value>' . Mage::helper('widget')->escapeHtml($value) . '</value>'
                    . '</action>';
            }
        }
        $xml .= '</block></reference>';

        return $xml;
    }

    private function isMultiArray($arr) {
        if (!is_array($arr))
            return false;
        foreach ($arr as $elm) {
            if (!is_array($elm))
                return false;
        }
        return true;
    }
}
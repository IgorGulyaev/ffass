<?php

/**
 * Copyright [2015] [Dexxtz]
 *
 * @package   Dexxtz_Productzoom
 * @author    Dexxtz
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 */

class Dexxtz_Productzoom_Model_System_Config_Source_Description_Position
{    
    public function toOptionArray()
    {
        $helper = Mage::helper('productzoom');
        return array(array("value" => "top" , "label" => $helper->__("Top")),
                     array("value" => "bottom" , "label" => $helper->__("Bottom")),                   
                    );
    }
}
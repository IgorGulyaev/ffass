<?php

/**
 * Copyright [2015] [Dexxtz]
 *
 * @package   Dexxtz_Productzoom
 * @author    Dexxtz
 * @license   http://www.apache.org/licenses/LICENSE-2.0
 */
 
class Dexxtz_Productzoom_Model_System_Config_Source_Thumbnails_Qty
{    
    public function toOptionArray()
    {
    	$helper = Mage::helper('productzoom');
        return array(array("value" => "1" , "label" => $helper->__("%s fixed thumbnail", 1)),
                     array("value" => "2" , "label" => $helper->__("%s fixed thumbnails", 2)),
                     array("value" => "3" , "label" => $helper->__("%s fixed thumbnails", 3)),
                     array("value" => "4" , "label" => $helper->__("%s fixed thumbnails", 4)),
                     array("value" => "5" , "label" => $helper->__("%s fixed thumbnails", 5)),
                     array("value" => "6" , "label" => $helper->__("%s fixed thumbnails", 6)),
                     array("value" => "7" , "label" => $helper->__("%s fixed thumbnails", 7)),
                     array("value" => "8" , "label" => $helper->__("%s fixed thumbnails", 8)),
                     array("value" => "9" , "label" => $helper->__("%s fixed thumbnails", 9)),
                     array("value" => "10" , "label" => $helper->__("%s fixed thumbnails", 10))                        
                    );
    }
}
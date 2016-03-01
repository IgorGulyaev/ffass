<?php
/**
 * Unicode Systems
 * @category   Uni
 * @package    Uni_Banner
 * @copyright  Copyright (c) 2010-2011 Unicode Systems. (http://www.unicodesystems.in)
 * @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */
class Uni_Banner_Model_Status extends Varien_Object {
    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 2;

    static public function getOptionArray() {
        return array(
            self::STATUS_ENABLED => Mage::helper('banner')->__('Enabled'),
            self::STATUS_DISABLED => Mage::helper('banner')->__('Disabled')
        );
    }

    static public function getAnimationArray() {
        $animations = array(
            array(
                'value' => 'Fade/Appear',
                'label' => Mage::helper('banner')->__('Fade / Appear'),
            ),
            array(
                'value' => 'Shake',
                'label' => Mage::helper('banner')->__('Shake'),
            ),

            array(
                'value' => 'Pulsate',
                'label' => Mage::helper('banner')->__('Pulsate'),
            ),
            array(
                'value' => 'Puff',
                'label' => Mage::helper('banner')->__('Puff'),
            ),
            array(
                'value' => 'Grow',
                'label' => Mage::helper('banner')->__('Grow'),
            ),
            array(
                'value' => 'Shrink',
                'label' => Mage::helper('banner')->__('Shrink'),
            ),
            array(
                'value' => 'Fold',
                'label' => Mage::helper('banner')->__('Fold'),
            ),         
            array(
                'value' => 'Squish',
                'label' => Mage::helper('banner')->__('Squish'),
            ),
   
            array(
                'value' => 'BlindUp',
                'label' => Mage::helper('banner')->__('Blindup'),
            ),
             array(
                'value' => 'BlindDown',
                'label' => Mage::helper('banner')->__('BlindDown'),
            ),            
            array(
                'value' => 'DropOut',
                'label' => Mage::helper('banner')->__('DropOut'),
            ),
        );
        array_unshift($animations, array('label' => '--Select--', 'value' => ''));
        return $animations;
    }

    static public function getPreAnimationArray() {
        return array(
            array(
                'value' => 'slide',
                'label' => Mage::helper('banner')->__('Slide'),
            ),
            array(
                'value' => 'fade',
                'label' => Mage::helper('banner')->__('Fade'),
            )
        );
    }

}
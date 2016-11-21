<?php
/**
 * Created by PhpStorm.
 * User: Vladimir Prutyan
 * Date: 6/8/2016
 * Time: 6:55 PM
 */


Mage::getModel('core/url_rewrite')->setIsSystem(0)->setOptions('RP')
    ->setIdPath('offers/index/index/clearance/1/')
    ->setTargetPath('offers/index/index/clearance/1/')
    ->setRequestPath('recently-viewed.html')->save();


<?php
/**
 * Created by PhpStorm.
 * User: Vladimir Prutyan
 * Date: 12/21/2015
 * Time: 3:51 PM
 */

class Ecomitize_Footernav_IndexController extends Mage_Core_Controller_Front_Action{

    public function indexAction(){

        $this->loadLayout();
        $this->renderLayout();
    }
}
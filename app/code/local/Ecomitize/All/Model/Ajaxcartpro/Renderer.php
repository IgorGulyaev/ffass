<?php
/**
 * Created by PhpStorm.
 * User: vasyl
 * Date: 03.02.16
 * Time: 16:46
 */ 
class Ecomitize_All_Model_Ajaxcartpro_Renderer extends AW_Ajaxcartpro_Model_Renderer
{
    /**
     * Add custom renderer
     */
    public function _construct()
    {
        $this->_sources['cartLink'] = 'ecomitize_all/renderer_cartlink';
        $this->_sources['miniCart'] = 'ecomitize_all/renderer_minicart';
    }
}
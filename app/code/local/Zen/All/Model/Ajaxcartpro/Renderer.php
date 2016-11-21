<?php
/**
 * Created by PhpStorm.
 * User: vasyl
 * Date: 03.02.16
 * Time: 16:46
 */ 
class Zen_All_Model_Ajaxcartpro_Renderer extends AW_Ajaxcartpro_Model_Renderer
{
    /**
     * Add custom renderer
     */
    public function _construct()
    {
        $this->_sources['cartLink'] = 'zen_all/renderer_cartlink';
        $this->_sources['miniCart'] = 'zen_all/renderer_minicart';
    }
}
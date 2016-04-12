<?php

class Ecomitize_Cmspages_Block_Cmspages extends Mage_Core_Block_Template implements Mage_Widget_Block_Interface
{
    protected function _allowShow($array_links)
    {
        $currentUrl = Mage::helper('core/url')->getCurrentUrl();
        $url = Mage::getSingleton('core/url')->parseUrl($currentUrl);
        $path = $url->getPath();
        $path = str_replace('/','',$path);

        if(in_array($path, $array_links)){
            return true;
        }else{
            return false;
        }
    }

    protected function _toHtml()
    {

        $html = '';
        $link_options = self::getData('cmspages_pages_option');

        if (empty($link_options)) {
            return $html;
        }

        $array_links = explode(',', $link_options);

        $allowShow = $this->_allowShow($array_links);

        if($allowShow){
            if (is_array($array_links) && count($array_links)) {
                foreach ($array_links as $option) {
                    $html .= '<li><a href="'.Mage::helper('cms/page')->getPageUrl( $option ).'">'.$option.'</a></li>';
                }
            }
        }

        return $html;
    }
}
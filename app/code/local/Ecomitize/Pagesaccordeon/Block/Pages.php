<?php

class Ecomitize_Pagesaccordeon_Block_Pages extends Mage_Core_Block_Template implements Mage_Widget_Block_Interface
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

    protected function _getPages($array_links, $optionsArray)
    {
        $cmsPagesCollection = Mage::getModel('cms/page')->getCollection()->addFieldToFilter(
            array('identifier'),
            array(
                array('in'=>$array_links),
            )
        );

        $i=0;
        foreach ($cmsPagesCollection as $page){

            $identifier = $page->getIdentifier();
            $option[$i]['label'] = $page->getTitle();
            $option[$i]['content'] = $page->getContent();
            $option[$i]['identifier'] = $identifier;
            $option[$i]['sort'] = $optionsArray[$identifier]['sort'];
            $option[$i]['type'] = $optionsArray[$identifier]['type'];

            $i++;
        }
        return $option;
    }

    protected function _getAccordeon($array_pages)
    {
        $html .= '<div class="panel-group" id="accordion">';

        foreach ($array_pages as $option) {
            if($option['type'] == 'link'){
                $link = '<a href="'.Mage::helper('cms/page')->getPageUrl( $option['identifier'] ).'">'.$option['label'].'</a>';
            }else{
                $link = '<a data-toggle="collapse" data-parent="#accordion" href="#'.$option['identifier'].'">'.$option['label'].'</a>';
            }
            $html .= '<div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    '.$link.'
                                </h4>
                            </div>
                            <div id="'.$option['identifier'].'" class="panel-collapse collapse">
                                <div class="panel-body">'.$option['content'].'</div>
                            </div>
                       </div>';
        }

        $html .= '</div>';
        return $html;
    }

    protected function _getTabs($array_pages)
    {
        $ul = '<ul class="nav nav-tabs">';
        $tabContent = '<div class="tab-content">';
        $i=0;

        foreach ($array_pages as $option) {

            if($option['type'] == 'link'){
                $link = '<a href="'.Mage::helper('cms/page')->getPageUrl( $option['identifier'] ).'">'.$option['label'].'</a>';
            }else{
                $link = '<a data-toggle="tab" href="#'.$option['identifier'].'">'.$option['label'].'</a>';
            }

            $li .= '<li class="'.($i==0?'active':'').'" >'.$link.'</li>';
            $tabs .= '<div id="'.$option['identifier'].'" class="tab-pane fade '.($i==0?'in active':'').'">'.$option['content'].'</div>';
            $i++;
        }

        $ul .= $li.'</ul>';
        $tabContent .= $tabs.'</div>';
        return $html = $ul.$tabContent;
    }

    protected function array_sort($array, $on)
    {
        $new_array = array();
        $sortable_array = array();

        if (count($array) > 0) {
            foreach ($array as $k => $v) {
                if (is_array($v)) {
                    foreach ($v as $k2 => $v2) {
                        if ($k2 == $on) {
                            $sortable_array[$k] = $v2;
                        }
                    }
                } else {
                    $sortable_array[$k] = $v;
                }
            }

            asort($sortable_array);

            foreach ($sortable_array as $k => $v) {
                $new_array[$k] = $array[$k];
            }
        }

        return $new_array;
    }

    protected function _toHtml()
    {
        $html = '';
        $pages_options = unserialize( self::getData('pagesaccordeon_option') );

        $menuType = self::getData('pagesaccordeon_type');
        $menuShow = self::getData('pagesaccordeon_where_show');
        $menuShow = explode(',', $menuShow);

        if (empty($pages_options)) {
            return $html;
        }

        foreach($pages_options as $key => $value){
            $array_links[] = $value['attribute'];
            $optionsArray[$value['attribute']]['sort'] = $value['tab_sort'];
            $optionsArray[$value['attribute']]['type'] = $value['type'];
        }

        $array_pages = $this->_getPages($array_links, $optionsArray);
        $array_pages = $this->array_sort($array_pages, 'sort');

        $allowShow = $this->_allowShow($menuShow);

        if($allowShow){
            if (is_array($array_pages) && count($array_pages)) {
                if($menuType == 'accordeon'){
                    $html .= $this->_getAccordeon($array_pages);
                }else{
                    $html .= $this->_getTabs($array_pages);
                }
            }
        }

        return $html;
    }

}
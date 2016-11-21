<?php

class Zen_Accordeoncontent_Block_Pages extends Mage_Core_Block_Template implements Mage_Widget_Block_Interface
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

    protected function _getAccordeon($array_pages)
    {
        $html .= '<div class="panel-group" id="accordion">';

        foreach ($array_pages as $key => $option) {
            if($option['type'] == 'link'){
                $content = strip_tags(str_replace(' ', '', $option['content']));
                $link = '<a href="'.Mage::getUrl( $content ).'">'.$option['title'].'</a>';
            }else{
                $link = '<a data-toggle="collapse" data-parent="#accordion" href="#'.$key.'">'.$option['title'].'</a>';
            }
            $html .= '<div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    '.$link.'
                                </h4>
                            </div>
                            <div id="'.$key.'" class="panel-collapse collapse">
                                <div class="panel-body">'.$option['content'].'</div>
                            </div>
                       </div>';
        }

        $html .= '</div>';
        return $html;
    }

    protected function _getTabs($array_pages)
    {
        $ul = '<ul class="pages-accordeon nav nav-tabs">';
        $tabContent = '<div class="pages-accordeon tab-content">';
        $i=0;

        foreach ($array_pages as $key => $option) {

            if($option['type'] == 'link'){
                $content = strip_tags(str_replace(' ', '', $option['content']));
                $link = '<a href="'.Mage::getUrl( $content ).'">'.$option['title'].'</a>';
            }else{
                $link = '<a data-toggle="tab" href="#'.$key.'">'.$option['title'].'</a>';
            }

            $li .= '<li class="'.($i==0?'active':'').'" >'.$link.'</li>';
            $tabs .= '<div id="'.$key.'" class="tab-pane fade '.($i==0?'in active':'').'">'.$option['content'].'</div>';
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
        $array_pages = unserialize( self::getData('accordeoncontent_option') );

        $menuType = self::getData('accordeoncontent_type');
        $menuShow = self::getData('accordeoncontent_where_show');
        $menuShow = explode(',', $menuShow);


        if (empty($array_pages)) {
            return $html;
        }

        foreach($pages_options as $key => $value){
            $array_links[] = $value['attribute'];
            $optionsArray[$value['attribute']]['sort'] = $value['tab_sort'];
            $optionsArray[$value['attribute']]['type'] = $value['type'];
        }

        $array_pages = $this->array_sort($array_pages, 'tab_sort');
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
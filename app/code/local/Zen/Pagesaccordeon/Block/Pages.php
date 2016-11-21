<?php

class Zen_Pagesaccordeon_Block_Pages extends Mage_Core_Block_Template implements Mage_Widget_Block_Interface
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

        foreach ($array_pages as $option) {
            if($option['custompagename']){
                $label = $option['custompagename'];
            }else{
                $label = $option['label'];
            }

            if($option['type'] == 'link'){

                if($option['linktype'] == 'inner'){
                    $href = Mage::getUrl( $option['link'] );
                }elseif( $option['linktype'] == 'cmspage'){
                    $href = Mage::helper('cms/page')->getPageUrl( $option['identifier'] );
                }else{
                    $href = $option['link'];
                }

                $link = '<a href="'.$href.'">'.$label.'</a>';
            }else{
                $link = '<a data-toggle="collapse" data-parent="#accordion" href="#'.$option['identifier'].'">'.$label.'</a>';
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
        $ul = '<ul class="pages-accordeon nav nav-tabs">';
        $tabContent = '<div class="pages-accordeon tab-content">';
        $i=0;

        foreach ($array_pages as $option) {

            if($option['custompagename']){
                $label = $option['custompagename'];
            }else{
                $label = $option['label'];
            }
            if($option['type'] == 'link'){

                if($option['linktype'] == 'inner'){
                    $href = Mage::getUrl( $option['link'] );
                }elseif( $option['linktype'] == 'cmspage'){
                    $href = Mage::helper('cms/page')->getPageUrl( $option['identifier'] );
                }else{
                    $href = $option['link'];
                }

                $link = '<a href="'.$href.'">'.$label.'</a>';
            }else{
                $link = '<a data-toggle="tab" href="#'.$option['identifier'].'">'.$label.'</a>';
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
            $pageModel = Mage::getModel('cms/page')->setStore(Mage::app()->getStore()->getId())->load($value['attribute'], 'identifier');
            $arrayResults[$key]['label'] = $pageModel->getTitle();
            $arrayResults[$key]['content'] = $pageModel->getContent();
            $arrayResults[$key]['identifier'] = $pageModel->getIdentifier();
            $arrayResults[$key]['sort'] = $value['tab_sort'];
            $arrayResults[$key]['type'] = $value['type'];
            $arrayResults[$key]['link'] = $value['link'];
            $arrayResults[$key]['linktype'] = $value['linktype'];
            $arrayResults[$key]['custompagename'] = $value['custompagename'];
        }

        $array_pages = $this->array_sort($arrayResults, 'sort');

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
<?php

class Ecomitize_Pagesaccordeon_Block_Pages extends Mage_Core_Block_Template implements Mage_Widget_Block_Interface
{
    protected function _getPages($array_links)
    {
        $cmsPagesCollection = Mage::getModel('cms/page')->getCollection()->addFieldToFilter(
            array('identifier'),
            array(
                array('in'=>$array_links),
            )
        );

        $i=0;
        foreach ($cmsPagesCollection as $page){
            $option[$i]['label'] = $page->getTitle();
            $option[$i]['content'] = $page->getContent();
            $option[$i]['identifier'] = $page->getIdentifier();
            $i++;
        }
        return $option;
    }

    protected function _getAccordeon($array_pages)
    {
        $html .= '<div class="panel-group" id="accordion">';

        foreach ($array_pages as $option) {
            $html .= '<div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#'.$option['identifier'].'">'.$option['label'].'</a>
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
            $li .= '<li class="'.($i==0?'active':'').'" ><a data-toggle="tab" href="#'.$option['identifier'].'">'.$option['label'].'</a></li>';
            $tabs .= '<div id="'.$option['identifier'].'" class="tab-pane fade '.($i==0?'in active':'').'">'.$option['content'].'</div>';
            $i++;
        }

        $ul .= $li.'</ul>';
        $tabContent .= $tabs.'</div>';
        return $html = $ul.$tabContent;
    }

    protected function _toHtml()
    {

        $html = '';
        $pages_options = self::getData('pagesaccordeon_option');

        $menuType = self::getData('pagesaccordeon_type');

        if (empty($pages_options)) {
            return $html;
        }

        $array_pages = explode(',', $pages_options);

        $array_pages = $this->_getPages($array_pages);

        if (is_array($array_pages) && count($array_pages)) {
            if($menuType == 'accordeon'){
                $html .= $this->_getAccordeon($array_pages);
            }else{
                $html .= $this->_getTabs($array_pages);
            }
        }

        return $html;
    }
}
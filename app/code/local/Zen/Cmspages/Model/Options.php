<?php

class Zen_Cmspages_Model_Options
{
    public function getAllOptions()
    {
        if (is_null($this->_options)) {
            $cmsPagesCollection = Mage::getModel('cms/page')->getCollection();

            $i=0;
            foreach ($cmsPagesCollection as $page){
                $option[$i]['label'] = $page->getTitle();
                $option[$i]['value'] = $page->getIdentifier();
                $i++;
            }
            $this->_options = $option;
        }
        return $this->_options;
    }

    public function toOptionArray()
    {
        return $this->getAllOptions();
    }


}
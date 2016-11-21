<?php

class Zen_Pagesaccordeon_Model_Observer {

    public function saveBefore(Varien_Event_Observer $observer)
    {
        $widget = $observer->getDataObject();
        $params = $widget->getWidgetParameters();

        if ( isset($params['pagesaccordeon_option']['__empty']) ) {
            unset($params['pagesaccordeon_option']['__empty']);
        }

        $observer->getDataObject()->setWidgetParameters(
            serialize($params)
        );
    }
}
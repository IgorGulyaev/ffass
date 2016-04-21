<?php

class Ecomitize_Accordeoncontent_Model_Observer {

    public function saveBefore(Varien_Event_Observer $observer)
    {
        $widget = $observer->getDataObject();
        $params = $widget->getWidgetParameters();

        if ( isset($params['accordeoncontent_option']['__empty']) ) {
            unset($params['accordeoncontent_option']['__empty']);
        }

        $observer->getDataObject()->setWidgetParameters(
            serialize($params)
        );
    }
}
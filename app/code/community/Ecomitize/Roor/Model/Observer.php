<?php

class Ecomitize_Roor_Model_Observer
{
    public function addLayoutXml($event)
    {
        $xml = $event->getUpdates()
                ->addChild('ecomitize_roor');
        $xml->addAttribute('module', 'Ecomitize_Roor');
        $xml->addChild('file', 'ecomitize_roor.xml');
    }
}

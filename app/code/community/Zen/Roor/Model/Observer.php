<?php

class Zen_Roor_Model_Observer
{
    public function addLayoutXml($event)
    {
        $xml = $event->getUpdates()
                ->addChild('zen_roor');
        $xml->addAttribute('module', 'Zen_Roor');
        $xml->addChild('file', 'zen_roor.xml');
    }
}

<?php
$installer = $this;

$installer->startSetup();

$this->_conn->addColumn($this->getTable('brand'), 'legend', 'text');

$installer->endSetup(); 
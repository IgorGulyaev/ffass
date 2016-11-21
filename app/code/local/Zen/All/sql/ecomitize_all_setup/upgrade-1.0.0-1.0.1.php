<?php

$installer = $this;

$installer->startSetup();

/** @var Magento_Db_Adapter_Pdo_Mysql $conn */
$conn = $installer->getConnection();
$res = $conn->query("SHOW TABLES LIKE 'permission_block'");

if ($res->rowCount()) {
    $conn->insertOnDuplicate('permission_block', array(
            array(
                'block_name' => 'ajaxcartpro/confirmation_items_continue',
                'is_allowed' => '1'
            ),
            array(
                'block_name' => 'ajaxcartpro/confirmation_items_gotocheckout',
                'is_allowed' => '1'
            )
        )
    );
}

$installer->endSetup();

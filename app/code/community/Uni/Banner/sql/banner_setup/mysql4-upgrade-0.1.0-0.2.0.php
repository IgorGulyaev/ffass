<?php

/**
 * Unicode Systems
 * @category   Uni
 * @package    Uni_Banner
 * @copyright  Copyright (c) 2010-2011 Unicode Systems. (http://www.unicodesystems.in)
 * @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

$installer = $this;

$installer->startSetup();

/** @var Magento_Db_Adapter_Pdo_Mysql $conn */
$conn = $installer->getConnection();
$res = $conn->query("SHOW TABLES LIKE 'permission_block'");

if ($res->rowCount()) {
    $conn->insertOnDuplicate('permission_block', array(
            array(
                'block_name' => 'banner/banner',
                'is_allowed' => '1'
            )
        )
    );
}

$installer->endSetup();

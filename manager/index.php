<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
session_start();
$_SESSION['passwd'] = false;
if($_REQUEST['password'] == ''){
    $_SESSION['passwd'] = true;
}

if ($_SESSION['passwd']) {
    error_reporting(E_ALL);

    require_once('../app/Mage.php');
    Varien_Profiler::enable();
//Mage::setIsDeveloperMode(true);
    ini_set('display_errors', 1);
    Mage::app('admin');
    Mage::getSingleton("core/session", array("name" => "adminhtml"));
    Mage::register('isSecureArea', true);


    if ($_REQUEST['action']) {
        if ($_REQUEST['action'] == 'Enable') {
            $sql = "UPDATE  `cataloginventory_stock_item` SET  `is_in_stock` =  '1' WHERE is_in_stock = 0 ";
// fetch write database connection that is used in Mage_Core module
            $write = Mage::getSingleton('core/resource')->getConnection('core_write');

// now $write is an instance of Zend_Db_Adapter_Abstract
            $result = $write->query($sql);
            $mensaje = 'El Stock Ha sido Habilitado';
        }

        if ($_REQUEST['action'] == 'Disable') {
            $sql = "UPDATE  `cataloginventory_stock_item` SET  `is_in_stock` =  '0' WHERE is_in_stock = 1";
// fetch write database connection that is used in Mage_Core module
            $write = Mage::getSingleton('core/resource')->getConnection('core_write');

// now $write is an instance of Zend_Db_Adapter_Abstract
            $result = $write->query($sql);
            $mensaje = 'El Stock Ha sido desabilitado';
        }




        for ($i = 1; $i <= 9; $i++) {
            Mage::getModel('index/process')->load($i)->reindexAll();
        }
    }
    ?>
    <label>Habilitar o desabilitar Stock en la tienda.</label>
    <h3><?php echo $mensaje; ?></h3>
    <form action="/manager/index.php" method="get">

        <input type="submit" value="Disable" name="action">  -  <input type="submit" value="Enable" name="action">

    </form>
    <?php
} else {
    ?>
    <label>Habilitar o desabilitar Stock en la tienda.</label>
    <h3><?php echo $mensaje; ?></h3>
    <form action="/manager/index.php" method="get">

        <input type="password" value="" name="password" />
        <input type="submit" value="Login" name="action"> 

    </form>
    <?php
}
?>

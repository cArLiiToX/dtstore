<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


require_once('app/Mage.php');
Mage::app('admin');
Mage::getSingleton("core/session", array("name" => "adminhtml"));
Mage::register('isSecureArea', true);


$obj_php = json_decode(file_get_contents('http://www.set-fx.com/stats?timestamp=1418426752515'));
$TRM = str_replace('.', '', $obj_php->trm);

$sql = 'UPDATE `directory_currency_rate` set rate= "'.number_format(pow(1/$TRM, 1),12).'" WHERE currency_from = "COP" and currency_to = "USD"';
// fetch write database connection that is used in Mage_Core module
$write = Mage::getSingleton('core/resource')->getConnection('core_write');

// now $write is an instance of Zend_Db_Adapter_Abstract
$result = $write->query($sql);

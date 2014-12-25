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


//SELECT * FROM  preguntas_products_pregunta_product as app, preguntas_products_pregunta as aps WHERE aps.entity_id = app.pregunta_id and  status = 1 and product_id = $id order by aps.entity_id DESC
//$sql = 'UPDATE `preguntas_products_pregunta` set name= "'.$_REQUEST['name'].'", email= "'.$_REQUEST['email'].'", pregunta= "'.$_REQUEST['pregunta'].'" WHERE ';

if (Mage::getSingleton('customer/session')->isLoggedIn()) {

    // Load the customer's data
    $customer = Mage::getSingleton('customer/session')->getCustomer();

    $sql = 'INSERT INTO `preguntas_products_pregunta` (name,email,pregunta,status,updated_at,created_at) values ("' . $customer->getName() . '","' . $customer->getEmail() . '","' . $_REQUEST['pregunta'] . '",1,"' . date('Y-m-d H:i:s') . '","' . date('Y-m-d H:i:s') . '"';

// fetch write database connection that is used in Mage_Core module
    $write = Mage::getSingleton('core/resource')->getConnection('core_write');
}



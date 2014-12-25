<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


require_once('app/Mage.php');

Mage::app(); 

// Define the path to the root of Magento installation.
define('ROOT', Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB));

// Obtain the general session and search for an item called 'customer_id'
$coreSession = Mage::getSingleton('core/session', array('name' => 'frontend'));
if(isset($coreSession['visitor_data']['customer_id'])){
    $customerId = $coreSession['visitor_data']['customer_id'];
} else {
    header('Location: '.ROOT.'customer/account/login/');
}

// Load the user session.
Mage::getSingleton('customer/session')->loginById($customerId);
$customerSession = Mage::getSingleton("customer/session");

// We verified that created successfully (not required)
if(!$customerSession->isLoggedIn()) {
    header('Location: '.ROOT.'customer/account/login/');
}

// Load customer
$customer = $customerSession->getCustomer();


//var_dump($customer);


//SELECT * FROM  preguntas_products_pregunta_product as app, preguntas_products_pregunta as aps WHERE aps.entity_id = app.pregunta_id and  status = 1 and product_id = $id order by aps.entity_id DESC
//$sql = 'UPDATE `preguntas_products_pregunta` set name= "'.$_REQUEST['name'].'", email= "'.$_REQUEST['email'].'", pregunta= "'.$_REQUEST['pregunta'].'" WHERE ';
//var_dump(Mage::getSingleton('customer/session')->isLoggedIn());

//var_dump( $_SESSION);
if ($customerSession->isLoggedIn()) {


    echo $sql = 'INSERT INTO `preguntas_products_pregunta` (name,email,pregunta,status,updated_at,created_at) values ("' . $customer->getName() . '","' 
. 
$customer->getEmail() . '","' . $_REQUEST['message'] . '",1,"' . date('Y-m-d H:i:s') . '","' . date('Y-m-d H:i:s') . '")';

// fetch write database connection that is used in Mage_Core module
    $write = Mage::getSingleton('core/resource')->getConnection('core_write');
	$write->query($sql);
    $lastInsertId = $write->lastInsertId();


echo $sql = 'INSERT INTO `preguntas_products_pregunta_product` (pregunta_id, product_id,position) values ("' . $lastInsertId . '","' . 
$_REQUEST['product_id'] . '",1)';
$write = Mage::getSingleton('core/resource')->getConnection('core_write'); 
        $write->query($sql);
    $lastInsertId = $write->lastInsertId();

    var_dump($lastInsertId);
}



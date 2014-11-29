<?php

require 'app/Mage.php';

if (!Mage::isInstalled()) {
    echo "Application is not installed yet, please complete install wizard first.";
    exit;
}

Mage::app('admin');
Mage::getSingleton("core/session", array("name" => "adminhtml"));
Mage::register('isSecureArea', true);

$orderCollection = Mage::getResourceModel('sales/order_collection');

$orderCollection
        ->addFieldToFilter('status', 'pending')
        ->addFieldToFilter('created_at', array(
            'lt' => new Zend_Db_Expr("DATE_ADD('" . now() . "', INTERVAL 2 DAY)")))
        ->getSelect()
        ->order('entity_id')
        ->limit(10);

$orders = "";
foreach ($orderCollection->getItems() as $order) {
    $orderModel = Mage::getModel('sales/order');
    $orderModel->load($order['entity_id']);


    if (!$orderModel->canCancel())
        continue;

    $orderIncrementId = $orderModel->getData()['increment_id'];

    var_dump($orderModel->getData()['increment_id']);
    $order = Mage::getModel('sales/order')->loadByIncrementId($orderIncrementId);
    $items = $order->getAllVisibleItems();
    foreach ($items as $i):
        $stock_item = Mage::getModel('cataloginventory/stock_item')->loadByProduct($i->getProductId());
                
        if (!$stock_item->getId()) {
            $stock_item->setData('product_id', $product_id);
            $stock_item->setData('stock_id', 1);
        }
        
        $ReStock = ($stock_item->getData('qty') + 1);
        $stock_item->setData('is_in_stock', 1); // is 0 or 1
        $stock_item->setData('qty', $ReStock); 
        
        $orderModel->cancel();
        $orderModel->setStatus('canceled');
        $orderModel->save();

        try {
            $stock_item->save();
        } catch (Exception $e) {
            echo "{$e}";
        }

    endforeach;
}
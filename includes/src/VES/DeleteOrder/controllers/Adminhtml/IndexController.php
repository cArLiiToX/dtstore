<?php
/**
 * VES_DeleteOrder_Adminhtml_PrintController
 *
 * @author		VnEcoms Team <support@vnecoms.com>
 * @website		http://www.vnecoms.com
 */

class VES_DeleteOrder_Adminhtml_IndexController extends Mage_Adminhtml_Controller_Action
{
	/**
	 * Delete Orders
	 */
	public function deleteOrdersAction(){
		$orderIds = $this->getRequest()->getParam('order_ids');
		if (empty($orderIds)) {
			Mage::getSingleton('adminhtml/session')->addError('There is no order to process');
			$this->_redirect('adminhtml/sales_order');
			return;
		}
		try{
			/*Delete invoices which references to orders*/
			$invoices = Mage::getResourceModel('sales/order_invoice_collection')->addAttributeToSelect('*')->addFieldToFilter('order_id',array('in',$orderIds));
			foreach($invoices as $invoice) $invoice->delete();
			
			$gridInvoices = Mage::getResourceModel('sales/order_invoice_grid_collection')->addFieldToFilter('order_id',array('in',$orderIds));
			foreach($gridInvoices as $invoice) $invoice->delete();
			
			/*Delete shipments which references to orders*/
			$shipments = Mage::getResourceModel('sales/order_shipment_collection')->addAttributeToSelect('*')->addFieldToFilter('order_id',array('in',$orderIds));
			foreach($shipments as $shipment) $shipment->delete();
			
			$gridShipments = Mage::getResourceModel('sales/order_shipment_grid_collection')->addAttributeToSelect('*')->addFieldToFilter('order_id',array('in',$orderIds));
			foreach($gridShipments as $shipment) $shipment->delete();
			
			/*Delete credit memos which references to orders*/
			$creditmemos = Mage::getResourceModel('sales/order_creditmemo_collection')->addAttributeToSelect('*')->addFieldToFilter('order_id',array('in',$orderIds));
			foreach($creditmemos as $creditmemo) $creditmemo->delete();
			
			$gridCreditMemos = Mage::getResourceModel('sales/order_creditmemo_grid_collection')->addAttributeToSelect('*')->addFieldToFilter('order_id',array('in',$orderIds));
			foreach($gridCreditMemos as $creditmemo) $creditmemo->delete();
			
			/*Delete transactions which references to orders*/
			$transactions = Mage::getResourceModel('sales/order_payment_transaction_collection')->addAttributeToSelect('*')->addFieldToFilter('order_id',array('in',$orderIds));
			foreach($transactions as $transaction) $transaction->delete();
			
			/*Delete orders */
			foreach($orderIds as $orderId) Mage::getModel('sales/order')->load($orderId)->delete();

			$gridOrders = Mage::getResourceModel('sales/order_grid_collection')->addAttributeToSelect('*')->addFieldToFilter('entity_id',array('in',$orderIds));
			foreach($gridOrders as $order) $order->delete();
			Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('%s has been deleted successfully',count($orderIds)));
		}catch(Exception $e){
			Mage::getSingleton('core/session')->addError($e->getMessage());
		}
		$this->_redirect('adminhtml/sales_order');
	}
}
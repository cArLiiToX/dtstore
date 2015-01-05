<?php
/**
 * VES_PdfPro_Model_Observer
 *
 * @author		VnEcoms Team <support@vnecoms.com>
 * @website		http://www.vnecoms.com
 */
class VES_DeleteOrder_Model_Observer
{
    public function core_block_abstract_to_html_before(Varien_Event_Observer $observer){
		if(!Mage::getStoreConfig('deleteorder/config/enabled')) return;
		$block = $observer->getEvent()->getBlock();
		if($block instanceof Mage_Adminhtml_Block_Widget_Grid_Massaction && $block->getRequest()->getControllerName() == 'sales_order')
		{
			$block->addItem('easypdf-delete-order', array(
				'label'=> 'Easy PDF - '.Mage::helper('deleteorder')->__('Delete Orders'),
				'url'  => Mage::getUrl('deleteorder_cp/adminhtml_index/deleteOrders'),
			));
		}
    }
}
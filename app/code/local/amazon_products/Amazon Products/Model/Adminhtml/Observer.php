<?php
/**
 * amazon_products_Amazon Products extension
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 * 
 * @category       amazon_products
 * @package        amazon_products_Amazon Products
 * @copyright      Copyright (c) 2014
 * @license        http://opensource.org/licenses/mit-license.php MIT License
 */
/**
 * Adminhtml observer
 *
 * @category    amazon_products
 * @package     amazon_products_Amazon Products
 * @author      Ultimate Module Creator
 */
class amazon_products_Amazon Products_Model_Adminhtml_Observer
{
    /**
     * check if tab can be added
     *
     * @access protected
     * @param Mage_Catalog_Model_Product $product
     * @return bool
     * @author Ultimate Module Creator
     */
    protected function _canAddTab($product)
    {
        if ($product->getId()) {
            return true;
        }
        if (!$product->getAttributeSetId()) {
            return false;
        }
        $request = Mage::app()->getRequest();
        if ($request->getParam('type') == 'configurable') {
            if ($request->getParam('attributes')) {
                return true;
            }
        }
        return false;
    }

    /**
     * add the amazon product tab to products
     *
     * @access public
     * @param Varien_Event_Observer $observer
     * @return amazon_products_Amazon Products_Model_Adminhtml_Observer
     * @author Ultimate Module Creator
     */
    public function addProductAmazonproductBlock($observer)
    {
        $block = $observer->getEvent()->getBlock();
        $product = Mage::registry('product');
        if ($block instanceof Mage_Adminhtml_Block_Catalog_Product_Edit_Tabs && $this->_canAddTab($product)) {
            $block->addTab(
                'amazonproducts',
                array(
                    'label' => Mage::helper('amazon_products_amazon products')->__('Amazon Products'),
                    'url'   => Mage::helper('adminhtml')->getUrl(
                        'adminhtml/amazon products_amazonproduct_catalog_product/amazonproducts',
                        array('_current' => true)
                    ),
                    'class' => 'ajax',
                )
            );
        }
        return $this;
    }

    /**
     * save amazonproduct - product relation
     *
     * @access public
     * @param Varien_Event_Observer $observer
     * @return amazon_products_Amazon Products_Model_Adminhtml_Observer
     * @author Ultimate Module Creator
     */
    public function saveProductAmazonproductData($observer)
    {
        $post = Mage::app()->getRequest()->getPost('amazonproduct_ids', -1);
        if ($post != '-1') {
            $post = explode(',', $post);
            $post = array_unique($post);
            $product = $observer->getEvent()->getProduct();
            Mage::getResourceSingleton('amazon_products_amazon products/amazonproduct_product')
                ->saveProductRelation($product, $post);
        }
        return $this;
    }

    /**
     * save amazon product - category relation
     *
     * @access public
     * @param Varien_Event_Observer $observer
     * @return amazon_products_Amazon Products_Model_Adminhtml_Observer
     * @author Ultimate Module Creator
     */
    public function saveCategoryAmazonproductData($observer)
    {
        $post = Mage::app()->getRequest()->getPost('amazonproduct_ids', -1);
        if ($post != '-1') {
            $post = explode(',', $post);
            $post = array_unique($post);
            $category = $observer->getEvent()->getCategory();
            Mage::getResourceSingleton('amazon_products_amazon products/amazonproduct_category')
                ->saveCategoryRelation($category, $post);
        }
        return $this;
    }
}

<?php
/**
 * Amazon_Products extension
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 * 
 * @category       Amazon
 * @package        Amazon_Products
 * @copyright      Copyright (c) 2014
 * @license        http://opensource.org/licenses/mit-license.php MIT License
 */
/**
 * Adminhtml observer
 *
 * @category    Amazon
 * @package     Amazon_Products
 * @author      Ultimate Module Creator
 */
class Amazon_Products_Model_Adminhtml_Observer
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
     * @return Amazon_Products_Model_Adminhtml_Observer
     * @author Ultimate Module Creator
     */
    public function addProductAmazonBlock($observer)
    {
        $block = $observer->getEvent()->getBlock();
        $product = Mage::registry('product');
        if ($block instanceof Mage_Adminhtml_Block_Catalog_Product_Edit_Tabs && $this->_canAddTab($product)) {
            $block->addTab(
                'amazons',
                array(
                    'label' => Mage::helper('amazon_products')->__('Amazon Products'),
                    'url'   => Mage::helper('adminhtml')->getUrl(
                        'adminhtml/products_amazon_catalog_product/amazons',
                        array('_current' => true)
                    ),
                    'class' => 'ajax',
                )
            );
        }
        return $this;
    }

    /**
     * save amazon product - product relation
     * @access public
     * @param Varien_Event_Observer $observer
     * @return Amazon_Products_Model_Adminhtml_Observer
     * @author Ultimate Module Creator
     */
    public function saveProductAmazonData($observer)
    {
        $post = Mage::app()->getRequest()->getPost('amazons', -1);
        if ($post != '-1') {
            $post = Mage::helper('adminhtml/js')->decodeGridSerializedInput($post);
            $product = Mage::registry('product');
            $amazonProduct = Mage::getResourceSingleton('amazon_products/amazon_product')
                ->saveProductRelation($product, $post);
        }
        return $this;
    }}

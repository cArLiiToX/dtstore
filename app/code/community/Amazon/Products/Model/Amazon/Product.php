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
 * Amazon Product product model
 *
 * @category    Amazon
 * @package     Amazon_Products
 * @author      Ultimate Module Creator
 */
class Amazon_Products_Model_Amazon_Product extends Mage_Core_Model_Abstract
{
    /**
     * Initialize resource
     *
     * @access protected
     * @return void
     * @author Ultimate Module Creator
     */
    protected function _construct()
    {
        $this->_init('amazon_products/amazon_product');
    }

    /**
     * Save data for amazon product-product relation
     * @access public
     * @param  Amazon_Products_Model_Amazon $amazon
     * @return Amazon_Products_Model_Amazon_Product
     * @author Ultimate Module Creator
     */
    public function saveAmazonRelation($amazon)
    {
        $data = $amazon->getProductsData();
        if (!is_null($data)) {
            $this->_getResource()->saveAmazonRelation($amazon, $data);
        }
        return $this;
    }

    /**
     * get products for amazon product
     *
     * @access public
     * @param Amazon_Products_Model_Amazon $amazon
     * @return Amazon_Products_Model_Resource_Amazon_Product_Collection
     * @author Ultimate Module Creator
     */
    public function getProductCollection($amazon)
    {
        $collection = Mage::getResourceModel('amazon_products/amazon_product_collection')
            ->addAmazonFilter($amazon);
        return $collection;
    }
}

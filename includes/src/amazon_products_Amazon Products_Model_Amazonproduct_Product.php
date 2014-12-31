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
 * Amazon Product product model
 *
 * @category    amazon_products
 * @package     amazon_products_Amazon Products
 * @author      Ultimate Module Creator
 */
class amazon_products_Amazon Products_Model_Amazonproduct_Product extends Mage_Core_Model_Abstract
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
        $this->_init('amazon_products_amazon products/amazonproduct_product');
    }

    /**
     * Save data for amazon product-product relation
     * @access public
     * @param  amazon_products_Amazon Products_Model_Amazonproduct $amazonproduct
     * @return amazon_products_Amazon Products_Model_Amazonproduct_Product
     * @author Ultimate Module Creator
     */
    public function saveAmazonproductRelation($amazonproduct)
    {
        $data = $amazonproduct->getProductsData();
        if (!is_null($data)) {
            $this->_getResource()->saveAmazonproductRelation($amazonproduct, $data);
        }
        return $this;
    }

    /**
     * get products for amazon product
     *
     * @access public
     * @param amazon_products_Amazon Products_Model_Amazonproduct $amazonproduct
     * @return amazon_products_Amazon Products_Model_Resource_Amazonproduct_Product_Collection
     * @author Ultimate Module Creator
     */
    public function getProductCollection($amazonproduct)
    {
        $collection = Mage::getResourceModel('amazon_products_amazon products/amazonproduct_product_collection')
            ->addAmazonproductFilter($amazonproduct);
        return $collection;
    }
}

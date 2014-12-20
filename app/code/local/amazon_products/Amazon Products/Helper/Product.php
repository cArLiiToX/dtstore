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
 * Product helper
 *
 * @category    amazon_products
 * @package     amazon_products_Amazon Products
 * @author      Ultimate Module Creator
 */
class amazon_products_Amazon Products_Helper_Product extends amazon_products_Amazon Products_Helper_Data
{

    /**
     * get the selected amazon products for a product
     *
     * @access public
     * @param Mage_Catalog_Model_Product $product
     * @return array()
     * @author Ultimate Module Creator
     */
    public function getSelectedAmazonproducts(Mage_Catalog_Model_Product $product)
    {
        if (!$product->hasSelectedAmazonproducts()) {
            $amazonproducts = array();
            foreach ($this->getSelectedAmazonproductsCollection($product) as $amazonproduct) {
                $amazonproducts[] = $amazonproduct;
            }
            $product->setSelectedAmazonproducts($amazonproducts);
        }
        return $product->getData('selected_amazonproducts');
    }

    /**
     * get amazon product collection for a product
     *
     * @access public
     * @param Mage_Catalog_Model_Product $product
     * @return amazon_products_Amazon Products_Model_Resource_Amazonproduct_Collection
     * @author Ultimate Module Creator
     */
    public function getSelectedAmazonproductsCollection(Mage_Catalog_Model_Product $product)
    {
        $collection = Mage::getResourceSingleton('amazon_products_amazon products/amazonproduct_collection')
            ->addProductFilter($product);
        return $collection;
    }
}

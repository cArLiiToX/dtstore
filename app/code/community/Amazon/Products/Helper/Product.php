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
 * Product helper
 *
 * @category    Amazon
 * @package     Amazon_Products
 * @author      Ultimate Module Creator
 */
class Amazon_Products_Helper_Product extends Amazon_Products_Helper_Data
{

    /**
     * get the selected amazon products for a product
     *
     * @access public
     * @param Mage_Catalog_Model_Product $product
     * @return array()
     * @author Ultimate Module Creator
     */
    public function getSelectedAmazons(Mage_Catalog_Model_Product $product)
    {
        if (!$product->hasSelectedAmazons()) {
            $amazons = array();
            foreach ($this->getSelectedAmazonsCollection($product) as $amazon) {
                $amazons[] = $amazon;
            }
            $product->setSelectedAmazons($amazons);
        }
        return $product->getData('selected_amazons');
    }

    /**
     * get amazon product collection for a product
     *
     * @access public
     * @param Mage_Catalog_Model_Product $product
     * @return Amazon_Products_Model_Resource_Amazon_Collection
     * @author Ultimate Module Creator
     */
    public function getSelectedAmazonsCollection(Mage_Catalog_Model_Product $product)
    {
        $collection = Mage::getResourceSingleton('amazon_products/amazon_collection')
            ->addProductFilter($product);
        return $collection;
    }
}

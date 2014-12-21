<?php
/**
 * amazonProducts_AmazonProducts extension
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 * 
 * @category       amazonProducts
 * @package        amazonProducts_AmazonProducts
 * @copyright      Copyright (c) 2014
 * @license        http://opensource.org/licenses/mit-license.php MIT License
 */
/**
 * Product helper
 *
 * @category    amazonProducts
 * @package     amazonProducts_AmazonProducts
 * @author      Ultimate Module Creator
 */
class amazonProducts_AmazonProducts_Helper_Product extends amazonProducts_AmazonProducts_Helper_Data
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
     * @return amazonProducts_AmazonProducts_Model_Resource_Amazonproduct_Collection
     * @author Ultimate Module Creator
     */
    public function getSelectedAmazonproductsCollection(Mage_Catalog_Model_Product $product)
    {
        $collection = Mage::getResourceSingleton('amazonproducts_amazonproducts/amazonproduct_collection')
            ->addProductFilter($product);
        return $collection;
    }
}

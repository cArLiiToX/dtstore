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
 * Amazon Product list on product page block
 *
 * @category    amazonProducts
 * @package     amazonProducts_AmazonProducts
 * @author      Ultimate Module Creator
 */
class amazonProducts_AmazonProducts_Block_Catalog_Product_List_Amazonproduct extends Mage_Catalog_Block_Product_Abstract
{
    /**
     * get the list of amazon products
     *
     * @access protected
     * @return amazonProducts_AmazonProducts_Model_Resource_Amazonproduct_Collection
     * @author Ultimate Module Creator
     */
    public function getAmazonproductCollection()
    {
        if (!$this->hasData('amazonproduct_collection')) {
            $product = Mage::registry('product');
            $collection = Mage::getResourceSingleton('amazonproducts_amazonproducts/amazonproduct_collection')
                ->addStoreFilter(Mage::app()->getStore())
                ->addFieldToFilter('status', 1)
                ->addProductFilter($product);
            $collection->getSelect()->order('related_product.position', 'ASC');
            $this->setData('amazonproduct_collection', $collection);
        }
        return $this->getData('amazonproduct_collection');
    }
}

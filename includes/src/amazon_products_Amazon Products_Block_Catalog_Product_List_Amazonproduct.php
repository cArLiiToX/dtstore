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
 * Amazon Product list on product page block
 *
 * @category    amazon_products
 * @package     amazon_products_Amazon Products
 * @author      Ultimate Module Creator
 */
class amazon_products_Amazon Products_Block_Catalog_Product_List_Amazonproduct extends Mage_Catalog_Block_Product_Abstract
{
    /**
     * get the list of amazon products
     *
     * @access protected
     * @return amazon_products_Amazon Products_Model_Resource_Amazonproduct_Collection
     * @author Ultimate Module Creator
     */
    public function getAmazonproductCollection()
    {
        if (!$this->hasData('amazonproduct_collection')) {
            $product = Mage::registry('product');
            $collection = Mage::getResourceSingleton('amazon_products_amazon products/amazonproduct_collection')
                ->setStoreId(Mage::app()->getStore()->getId())
                ->addAttributeToSelect('name', 1)
                ->addAttributeToFilter('status', 1)
                ->addProductFilter($product);
            $collection->getSelect()->order('related_product.position', 'ASC');
            $this->setData('amazonproduct_collection', $collection);
        }
        return $this->getData('amazonproduct_collection');
    }
}

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
 * Amazon Product list on product page block
 *
 * @category    Amazon
 * @package     Amazon_Products
 * @author      Ultimate Module Creator
 */
class Amazon_Products_Block_Catalog_Product_List_Amazon extends Mage_Catalog_Block_Product_Abstract
{
    /**
     * get the list of amazon products
     *
     * @access protected
     * @return Amazon_Products_Model_Resource_Amazon_Collection
     * @author Ultimate Module Creator
     */
    public function getAmazonCollection()
    {
        if (!$this->hasData('amazon_collection')) {
            $product = Mage::registry('product');
            $collection = Mage::getResourceSingleton('amazon_products/amazon_collection')
                ->addFieldToFilter('status', 1)
                ->addProductFilter($product);
            $collection->getSelect()->order('related_product.position', 'ASC');
            $this->setData('amazon_collection', $collection);
        }
        return $this->getData('amazon_collection');
    }
}

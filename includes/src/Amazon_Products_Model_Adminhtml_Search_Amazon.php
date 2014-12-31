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
 * Admin search model
 *
 * @category    Amazon
 * @package     Amazon_Products
 * @author      Ultimate Module Creator
 */
class Amazon_Products_Model_Adminhtml_Search_Amazon extends Varien_Object
{
    /**
     * Load search results
     *
     * @access public
     * @return Amazon_Products_Model_Adminhtml_Search_Amazon
     * @author Ultimate Module Creator
     */
    public function load()
    {
        $arr = array();
        if (!$this->hasStart() || !$this->hasLimit() || !$this->hasQuery()) {
            $this->setResults($arr);
            return $this;
        }
        $collection = Mage::getResourceModel('amazon_products/amazon_collection')
            ->addFieldToFilter('name', array('like' => $this->getQuery().'%'))
            ->setCurPage($this->getStart())
            ->setPageSize($this->getLimit())
            ->load();
        foreach ($collection->getItems() as $amazon) {
            $arr[] = array(
                'id'          => 'amazon/1/'.$amazon->getId(),
                'type'        => Mage::helper('amazon_products')->__('Amazon Product'),
                'name'        => $amazon->getName(),
                'description' => $amazon->getName(),
                'url' => Mage::helper('adminhtml')->getUrl(
                    '*/products_amazon/edit',
                    array('id'=>$amazon->getId())
                ),
            );
        }
        $this->setResults($arr);
        return $this;
    }
}

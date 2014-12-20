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
 * Amazon Product - product relation resource model collection
 *
 * @category    amazon_products
 * @package     amazon_products_Amazon Products
 * @author      Ultimate Module Creator
 */
class amazon_products_Amazon Products_Model_Resource_Amazonproduct_Product_Collection extends Mage_Catalog_Model_Resource_Product_Collection
{
    /**
     * remember if fields have been joined
     *
     * @var bool
     */
    protected $_joinedFields = false;

    /**
     * join the link table
     *
     * @access public
     * @return amazon_products_Amazon Products_Model_Resource_Amazonproduct_Product_Collection
     * @author Ultimate Module Creator
     */
    public function joinFields()
    {
        if (!$this->_joinedFields) {
            $this->getSelect()->join(
                array('related' => $this->getTable('amazon_products_amazon products/amazonproduct_product')),
                'related.product_id = e.entity_id',
                array('position')
            );
            $this->_joinedFields = true;
        }
        return $this;
    }

    /**
     * add amazon product filter
     *
     * @access public
     * @param amazon_products_Amazon Products_Model_Amazonproduct | int $amazonproduct
     * @return amazon_products_Amazon Products_Model_Resource_Amazonproduct_Product_Collection
     * @author Ultimate Module Creator
     */
    public function addAmazonproductFilter($amazonproduct)
    {
        if ($amazonproduct instanceof amazon_products_Amazon Products_Model_Amazonproduct) {
            $amazonproduct = $amazonproduct->getId();
        }
        if (!$this->_joinedFields ) {
            $this->joinFields();
        }
        $this->getSelect()->where('related.amazonproduct_id = ?', $amazonproduct);
        return $this;
    }
}

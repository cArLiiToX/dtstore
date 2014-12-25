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
 * Amazon Product - product relation resource model collection
 *
 * @category    Amazon
 * @package     Amazon_Products
 * @author      Ultimate Module Creator
 */
class Amazon_Products_Model_Resource_Amazon_Product_Collection extends Mage_Catalog_Model_Resource_Product_Collection
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
     * @return Amazon_Products_Model_Resource_Amazon_Product_Collection
     * @author Ultimate Module Creator
     */
    public function joinFields()
    {
        if (!$this->_joinedFields) {
            $this->getSelect()->join(
                array('related' => $this->getTable('amazon_products/amazon_product')),
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
     * @param Amazon_Products_Model_Amazon | int $amazon
     * @return Amazon_Products_Model_Resource_Amazon_Product_Collection
     * @author Ultimate Module Creator
     */
    public function addAmazonFilter($amazon)
    {
        if ($amazon instanceof Amazon_Products_Model_Amazon) {
            $amazon = $amazon->getId();
        }
        if (!$this->_joinedFields ) {
            $this->joinFields();
        }
        $this->getSelect()->where('related.amazon_id = ?', $amazon);
        return $this;
    }
}

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
 * Amazon Product - product relation model
 *
 * @category    Amazon
 * @package     Amazon_Products
 * @author      Ultimate Module Creator
 */
class Amazon_Products_Model_Resource_Amazon_Product extends Mage_Core_Model_Resource_Db_Abstract
{
    /**
     * initialize resource model
     *
     * @access protected
     * @see Mage_Core_Model_Resource_Abstract::_construct()
     * @author Ultimate Module Creator
     */
    protected function  _construct()
    {
        $this->_init('amazon_products/amazon_product', 'rel_id');
    }
    /**
     * Save amazon product - product relations
     *
     * @access public
     * @param Amazon_Products_Model_Amazon $amazon
     * @param array $data
     * @return Amazon_Products_Model_Resource_Amazon_Product
     * @author Ultimate Module Creator
     */
    public function saveAmazonRelation($amazon, $data)
    {
        if (!is_array($data)) {
            $data = array();
        }
        $deleteCondition = $this->_getWriteAdapter()->quoteInto('amazon_id=?', $amazon->getId());
        $this->_getWriteAdapter()->delete($this->getMainTable(), $deleteCondition);

        foreach ($data as $productId => $info) {
            $this->_getWriteAdapter()->insert(
                $this->getMainTable(),
                array(
                    'amazon_id' => $amazon->getId(),
                    'product_id'    => $productId,
                    'position'      => @$info['position']
                )
            );
        }
        return $this;
    }

    /**
     * Save  product - amazon product relations
     *
     * @access public
     * @param Mage_Catalog_Model_Product $prooduct
     * @param array $data
     * @return Amazon_Products_Model_Resource_Amazon_Product
     * @@author Ultimate Module Creator
     */
    public function saveProductRelation($product, $data)
    {
        if (!is_array($data)) {
            $data = array();
        }
        $deleteCondition = $this->_getWriteAdapter()->quoteInto('product_id=?', $product->getId());
        $this->_getWriteAdapter()->delete($this->getMainTable(), $deleteCondition);

        foreach ($data as $amazonId => $info) {
            $this->_getWriteAdapter()->insert(
                $this->getMainTable(),
                array(
                    'amazon_id' => $amazonId,
                    'product_id'    => $product->getId(),
                    'position'      => @$info['position']
                )
            );
        }
        return $this;
    }
}

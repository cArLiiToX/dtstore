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
 * Amazon Product - product relation model
 *
 * @category    amazonProducts
 * @package     amazonProducts_AmazonProducts
 * @author      Ultimate Module Creator
 */
class amazonProducts_AmazonProducts_Model_Resource_Amazonproduct_Product extends Mage_Core_Model_Resource_Db_Abstract
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
        $this->_init('amazonproducts_amazonproducts/amazonproduct_product', 'rel_id');
    }

    /**
     * Save amazon product - product relations
     *
     * @access public
     * @param amazonProducts_AmazonProducts_Model_Amazonproduct $amazonproduct
     * @param array $data
     * @return amazonProducts_AmazonProducts_Model_Resource_Amazonproduct_Product
     * @author Ultimate Module Creator
     */
    public function saveAmazonproductRelation($amazonproduct, $data)
    {
        if (!is_array($data)) {
            $data = array();
        }
        $deleteCondition = $this->_getWriteAdapter()->quoteInto('amazonproduct_id=?', $amazonproduct->getId());
        $this->_getWriteAdapter()->delete($this->getMainTable(), $deleteCondition);

        foreach ($data as $productId => $info) {
            $this->_getWriteAdapter()->insert(
                $this->getMainTable(),
                array(
                    'amazonproduct_id' => $amazonproduct->getId(),
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
     * @return amazonProducts_AmazonProducts_Model_Resource_Amazonproduct_Product
     * @author Ultimate Module Creator
     */
    public function saveProductRelation($product, $amazonproductIds)
    {
        $oldAmazonproducts = Mage::helper('amazonproducts_amazonproducts/product')->getSelectedAmazonproducts($product);
        $oldAmazonproductIds = array();
        foreach ($oldAmazonproducts as $amazonproduct) {
            $oldAmazonproductIds[] = $amazonproduct->getId();
        }
        $insert = array_diff($amazonproductIds, $oldAmazonproductIds);
        $delete = array_diff($oldAmazonproductIds, $amazonproductIds);
        $write = $this->_getWriteAdapter();
        if (!empty($insert)) {
            $data = array();
            foreach ($insert as $amazonproductId) {
                if (empty($amazonproductId)) {
                    continue;
                }
                $data[] = array(
                    'amazonproduct_id' => (int)$amazonproductId,
                    'product_id'  => (int)$product->getId(),
                    'position'=> 1
                );
            }
            if ($data) {
                $write->insertMultiple($this->getMainTable(), $data);
            }
        }
        if (!empty($delete)) {
            foreach ($delete as $amazonproductId) {
                $where = array(
                    'product_id = ?'  => (int)$product->getId(),
                    'amazonproduct_id = ?' => (int)$amazonproductId,
                );
                $write->delete($this->getMainTable(), $where);
            }
        }
        return $this;
    }
}

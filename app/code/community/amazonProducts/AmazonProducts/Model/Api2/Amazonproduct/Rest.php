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
 * Amazon Product abstract REST API handler model
 *
 * @category    amazonProducts
 * @package     amazonProducts_AmazonProducts
 * @author      Ultimate Module Creator
 */
abstract class amazonProducts_AmazonProducts_Model_Api2_Amazonproduct_Rest extends amazonProducts_AmazonProducts_Model_Api2_Amazonproduct
{
    /**
     * current amazon product
     */
    protected $_amazonproduct;

    /**
     * retrieve entity
     *
     * @access protected
     * @return array|mixed
     * @author Ultimate Module Creator
     */
    protected function _retrieve() {
        $amazonproduct = $this->_getAmazonproduct();
        $this->_prepareAmazonproductForResponse($amazonproduct);
        return $amazonproduct->getData();
    }

    /**
     * get collection
     *
     * @access protected
     * @return array
     * @author Ultimate Module Creator
     */
    protected function _retrieveCollection() {
        $collection = Mage::getResourceModel('amazonproducts_amazonproducts/amazonproduct_collection');
        $entityOnlyAttributes = $this->getEntityOnlyAttributes(
            $this->getUserType(),
            Mage_Api2_Model_Resource::OPERATION_ATTRIBUTE_READ
        );
        $availableAttributes = array_keys($this->getAvailableAttributes(
            $this->getUserType(),
            Mage_Api2_Model_Resource::OPERATION_ATTRIBUTE_READ)
        );
        $collection->addFieldToFilter('status', array('eq' => 1));
        $collection->addFieldToFilter('entity_id', array('neq'=>Mage::helper('amazonproducts_amazonproducts/amazonproduct')->getRootAmazonproductId()));
        $store = $this->_getStore();
        $collection->addStoreFilter($store->getId());
        $this->_applyCollectionModifiers($collection);
        $amazonproducts = $collection->load();
        $amazonproducts->walk('afterLoad');
        foreach ($amazonproducts as $amazonproduct) {
            $this->_setAmazonproduct($amazonproduct);
            $this->_prepareAmazonproductForResponse($amazonproduct);
        }
        $amazonproductsArray = $amazonproducts->toArray();
        $amazonproductsArray = $amazonproductsArray['items'];

        return $amazonproductsArray;
    }

    /**
     * prepare amazon product for response
     *
     * @access protected
     * @param amazonProducts_AmazonProducts_Model_Amazonproduct $amazonproduct
     * @author Ultimate Module Creator
     */
    protected function _prepareAmazonproductForResponse(amazonProducts_AmazonProducts_Model_Amazonproduct $amazonproduct) {
        $amazonproductData = $amazonproduct->getData();
    }

    /**
     * create amazon product
     *
     * @access protected
     * @param array $data
     * @return string|void
     * @author Ultimate Module Creator
     */
    protected function _create(array $data) {
        $this->_critical(self::RESOURCE_METHOD_NOT_ALLOWED);
    }

    /**
     * update amazon product
     *
     * @access protected
     * @param array $data
     * @author Ultimate Module Creator
     */
    protected function _update(array $data) {
        $this->_critical(self::RESOURCE_METHOD_NOT_ALLOWED);
    }

    /**
     * delete amazon product
     *
     * @access protected
     * @author Ultimate Module Creator
     */
    protected function _delete() {
        $this->_critical(self::RESOURCE_METHOD_NOT_ALLOWED);
    }

    /**
     * delete current amazon product
     *
     * @access protected
     * @param amazonProducts_AmazonProducts_Model_Amazonproduct $amazonproduct
     * @author Ultimate Module Creator
     */
    protected function _setAmazonproduct(amazonProducts_AmazonProducts_Model_Amazonproduct $amazonproduct) {
        $this->_amazonproduct = $amazonproduct;
    }

    /**
     * get current amazon product
     *
     * @access protected
     * @return amazonProducts_AmazonProducts_Model_Amazonproduct
     * @author Ultimate Module Creator
     */
    protected function _getAmazonproduct() {
        if (is_null($this->_amazonproduct)) {
            $amazonproductId = $this->getRequest()->getParam('id');
            $amazonproduct = Mage::getModel('amazonproducts_amazonproducts/amazonproduct');
            $amazonproduct->load($amazonproductId);
            if (!($amazonproduct->getId())) {
                $this->_critical(self::RESOURCE_NOT_FOUND);
            }
            if ($this->_getStore()->getId()) {
                $isValidStore = count(array_intersect(array(0, $this->_getStore()->getId()), $amazonproduct->getStoreId()));
                if (!$isValidStore) {
                    $this->_critical(self::RESOURCE_NOT_FOUND);
                }
            }
            $this->_amazonproduct = $amazonproduct;
        }
        return $this->_amazonproduct;
    }
}

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
 * Amazon Product abstract REST API handler model
 *
 * @category    Amazon
 * @package     Amazon_Products
 * @author      Ultimate Module Creator
 */
abstract class Amazon_Products_Model_Api2_Amazon_Rest extends Amazon_Products_Model_Api2_Amazon
{
    /**
     * current amazon product
     */
    protected $_amazon;

    /**
     * retrieve entity
     *
     * @access protected
     * @return array|mixed
     * @author Ultimate Module Creator
     */
    protected function _retrieve() {
        $amazon = $this->_getAmazon();
        $this->_prepareAmazonForResponse($amazon);
        return $amazon->getData();
    }

    /**
     * get collection
     *
     * @access protected
     * @return array
     * @author Ultimate Module Creator
     */
    protected function _retrieveCollection() {
        $collection = Mage::getResourceModel('amazon_products/amazon_collection')->addAttributeToSelect('*');
        $collection->setStoreId($this->_getStore()->getId());
        $entityOnlyAttributes = $this->getEntityOnlyAttributes(
            $this->getUserType(),
            Mage_Api2_Model_Resource::OPERATION_ATTRIBUTE_READ
        );
        $availableAttributes = array_keys($this->getAvailableAttributes(
            $this->getUserType(),
            Mage_Api2_Model_Resource::OPERATION_ATTRIBUTE_READ)
        );
        $collection->addAttributeToFilter('status', array('eq' => 1));
        $this->_applyCollectionModifiers($collection);
        $amazons = $collection->load();
        $amazons->walk('afterLoad');
        foreach ($amazons as $amazon) {
            $this->_setAmazon($amazon);
            $this->_prepareAmazonForResponse($amazon);
        }
        $amazonsArray = $amazons->toArray();
        return $amazonsArray;
    }

    /**
     * prepare amazon product for response
     *
     * @access protected
     * @param Amazon_Products_Model_Amazon $amazon
     * @author Ultimate Module Creator
     */
    protected function _prepareAmazonForResponse(Amazon_Products_Model_Amazon $amazon) {
        $amazonData = $amazon->getData();
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
     * @param Amazon_Products_Model_Amazon $amazon
     * @author Ultimate Module Creator
     */
    protected function _setAmazon(Amazon_Products_Model_Amazon $amazon) {
        $this->_amazon = $amazon;
    }

    /**
     * get current amazon product
     *
     * @access protected
     * @return Amazon_Products_Model_Amazon
     * @author Ultimate Module Creator
     */
    protected function _getAmazon() {
        if (is_null($this->_amazon)) {
            $amazonId = $this->getRequest()->getParam('id');
            $amazon = Mage::getModel('amazon_products/amazon');
            $storeId = $this->_getStore()->getId();
            $amazon->setStoreId($storeId);
            $amazon->load($amazonId);
            if (!($amazon->getId())) {
                $this->_critical(self::RESOURCE_NOT_FOUND);
            }
            $this->_amazon = $amazon;
        }
        return $this->_amazon;
    }
}

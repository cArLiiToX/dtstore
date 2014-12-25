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
 * Amazon Product REST API admin handler
 *
 * @category    Amazon
 * @package     Amazon_Products
 * @author      Ultimate Module Creator
 */
class Amazon_Products_Model_Api2_Amazon_Rest_Admin_V1 extends Amazon_Products_Model_Api2_Amazon_Rest
{

    /**
     * Remove specified keys from associative or indexed array
     *
     * @access protected
     * @param array $array
     * @param array $keys
     * @param bool $dropOrigKeys if true - return array as indexed array
     * @return array
     * @author Ultimate Module Creator
     */
    protected function _filterOutArrayKeys(array $array, array $keys, $dropOrigKeys = false) {
        $isIndexedArray = is_array(reset($array));
        if ($isIndexedArray) {
            foreach ($array as &$value) {
                if (is_array($value)) {
                    $value = array_diff_key($value, array_flip($keys));
                }
            }
            if ($dropOrigKeys) {
                $array = array_values($array);
            }
            unset($value);
        } else {
            $array = array_diff_key($array, array_flip($keys));
        }
        return $array;
    }

    /**
     * Retrieve list of amazon products
     *
     * @access protected
     * @return array
     * @author Ultimate Module Creator
     */
    protected function _retrieveCollection() {
        $collection = Mage::getResourceModel('amazon_products/amazon_collection')->addAttributeToSelect('*');
        $collection->setStoreId($this->_getStore()->getId());
        $entityOnlyAttributes = $this->getEntityOnlyAttributes($this->getUserType(),
            Mage_Api2_Model_Resource::OPERATION_ATTRIBUTE_READ);
        $availableAttributes = array_keys($this->getAvailableAttributes($this->getUserType(),
            Mage_Api2_Model_Resource::OPERATION_ATTRIBUTE_READ));
        $this->_applyCollectionModifiers($collection);
        $amazons = $collection->load();

        foreach ($amazons as $amazon) {
            $this->_setAmazon($amazon);
            $this->_prepareAmazonForResponse($amazon);
        }
        $amazonsArray = $amazons->toArray();
        return $amazonsArray;
    }

    /**
     * Delete amazon product by its ID
     *
     * @access protected
     * @throws Mage_Api2_Exception
     * @author Ultimate Module Creator
     */
    protected function _delete() {
        $amazon = $this->_getAmazon();
        try {
            $amazon->delete();
        } catch (Mage_Core_Exception $e) {
            $this->_critical($e->getMessage(), Mage_Api2_Model_Server::HTTP_INTERNAL_ERROR);
        } catch (Exception $e) {
            $this->_critical(self::RESOURCE_INTERNAL_ERROR);
        }
    }

    /**
     * Create amazon product
     *
     * @access protected
     * @param array $data
     * @return string
     * @author Ultimate Module Creator
     */
    protected function _create(array $data) {
        $amazon = Mage::getModel('amazon_products/amazon')->setData($data);
        try {
            $amazon->save();
        }
        catch (Mage_Core_Exception $e) {
            $this->_critical($e->getMessage(), Mage_Api2_Model_Server::HTTP_INTERNAL_ERROR);
        } catch (Exception $e) {
            $this->_critical(self::RESOURCE_UNKNOWN_ERROR);
        }
        return $this->_getLocation($amazon->getId());
    }

    /**
     * Update amazon product by its ID
     *
     * @access protected
     * @param array $data
     * @author Ultimate Module Creator
     */
    protected function _update(array $data) {
        $amazon = $this->_getAmazon();
        $amazon->addData($data);
        try {
            $amazon->save();
        } catch (Mage_Core_Exception $e) {
            $this->_critical($e->getMessage(), Mage_Api2_Model_Server::HTTP_INTERNAL_ERROR);
        } catch (Exception $e) {
            $this->_critical(self::RESOURCE_UNKNOWN_ERROR);
        }
    }

    /**
     * Set additional data before amazon product save
     *
     * @access protected
     * @param Amazon_Products_Model_Amazon $entity
     * @param array $amazonData
     * @author Ultimate Module Creator
     */
    protected function _prepareDataForSave($product, $productData) {
        //add your data processing algorithm here if needed
    }
}
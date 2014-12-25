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
class Amazon_Products_Model_Amazon_Api extends Mage_Api_Model_Resource_Abstract
{
    protected $_defaultAttributeList = array(
        'name', 
        'link', 
        'status', 
        'created_at', 
        'updated_at', 
    );


    /**
     * init amazon product
     *
     * @access protected
     * @param $amazonId
     * @return Amazon_Products_Model_Amazon
     * @author      Ultimate Module Creator
     */
    protected function _initAmazon($amazonId)
    {
        $amazon = Mage::getModel('amazon_products/amazon')->load($amazonId);
        if (!$amazon->getId()) {
            $this->_fault('amazon_not_exists');
        }
        return $amazon;
    }

    /**
     * get amazon products
     *
     * @access public
     * @param mixed $filters
     * @return array
     * @author Ultimate Module Creator
     */
    public function items($filters = null)
    {
        $collection = Mage::getModel('amazon_products/amazon')->getCollection()
            ->addAttributeToSelect('*');
        $apiHelper = Mage::helper('api');
        $filters = $apiHelper->parseFilters($filters);
        try {
            foreach ($filters as $field => $value) {
                $collection->addFieldToFilter($field, $value);
            }
        } catch (Mage_Core_Exception $e) {
            $this->_fault('filters_invalid', $e->getMessage());
        }
        $result = array();
        foreach ($collection as $amazon) {
            $result[] = $this->_getApiData($amazon);
        }
        return $result;
    }

    /**
     * Add amazon product
     *
     * @access public
     * @param array $data
     * @return array
     * @author Ultimate Module Creator
     */
    public function add($data)
    {
        try {
            if (is_null($data)) {
                throw new Exception(Mage::helper('amazon_products')->__("Data cannot be null"));
            }
            $data = (array)$data;
            if (isset($data['additional_attributes']) && is_array($data['additional_attributes'])) {
                foreach ($data['additional_attributes'] as $key=>$value) {
                    $data[$key] = $value;
                }
                unset($data['additional_attributes']);
            }
            $data['attribute_set_id'] = Mage::getModel('amazon_products/amazon')->getDefaultAttributeSetId();
            $amazon = Mage::getModel('amazon_products/amazon')
                ->setData((array)$data)
                ->save();
        } catch (Mage_Core_Exception $e) {
            $this->_fault('data_invalid', $e->getMessage());
        } catch (Exception $e) {
            $this->_fault('data_invalid', $e->getMessage());
        }
        return $amazon->getId();
    }

    /**
     * Change existing amazon product information
     *
     * @access public
     * @param int $amazonId
     * @param array $data
     * @return bool
     * @author Ultimate Module Creator
     */
    public function update($amazonId, $data)
    {
        $amazon = $this->_initAmazon($amazonId);
        try {
            $data = (array)$data;
            if (isset($data['additional_attributes']) && is_array($data['additional_attributes'])) {
                foreach ($data['additional_attributes'] as $key=>$value) {
                    $data[$key] = $value;
                }
                unset($data['additional_attributes']);
            }
            $amazon->addData($data);
            $amazon->save();
        }
        catch (Mage_Core_Exception $e) {
            $this->_fault('save_error', $e->getMessage());
        }

        return true;
    }

    /**
     * remove amazon product
     *
     * @access public
     * @param int $amazonId
     * @return bool
     * @author Ultimate Module Creator
     */
    public function remove($amazonId)
    {
        $amazon = $this->_initAmazon($amazonId);
        try {
            $amazon->delete();
        } catch (Mage_Core_Exception $e) {
            $this->_fault('remove_error', $e->getMessage());
        }
        return true;
    }

    /**
     * get info
     *
     * @access public
     * @param int $amazonId
     * @return array
     * @author Ultimate Module Creator
     */
    public function info($amazonId)
    {
        $result = array();
        $amazon = $this->_initAmazon($amazonId);
        $result = $this->_getApiData($amazon);
        //related products
        $result['products'] = array();
        $relatedProductsCollection = $amazon->getSelectedProductsCollection();
        foreach ($relatedProductsCollection as $product) {
            $result['products'][$product->getId()] = $product->getPosition();
        }
        return $result;
    }
    /**
     * Assign product to amazon product
     *
     * @access public
     * @param int $amazonId
     * @param int $productId
     * @param int $position
     * @return boolean
     * @author Ultimate Module Creator
     */
    public function assignProduct($amazonId, $productId, $position = null)
    {
        $amazon = $this->_initAmazon($amazonId);
        $positions    = array();
        $products     = $amazon->getSelectedProducts();
        foreach ($products as $product) {
            $positions[$product->getId()] = array('position'=>$product->getPosition());
        }
        $product = Mage::getModel('catalog/product')->load($productId);
        if (!$product->getId()) {
            $this->_fault('product_not_exists');
        }
        $positions[$productId]['position'] = $position;
        $amazon->setProductsData($positions);
        try {
            $amazon->save();
        } catch (Mage_Core_Exception $e) {
            $this->_fault('data_invalid', $e->getMessage());
        }
        return true;
    }

    /**
     * remove product from amazon product
     *
     * @access public
     * @param int $amazonId
     * @param int $productId
     * @return boolean
     * @author Ultimate Module Creator
     */
    public function unassignProduct($amazonId, $productId)
    {
        $amazon = $this->_initAmazon($amazonId);
        $positions    = array();
        $products     = $amazon->getSelectedProducts();
        foreach ($products as $product) {
            $positions[$product->getId()] = array('position'=>$product->getPosition());
        }
        unset($positions[$productId]);
        $amazon->setProductsData($positions);
        try {
            $amazon->save();
        } catch (Mage_Core_Exception $e) {
            $this->_fault('data_invalid', $e->getMessage());
        }
        return true;
    }

    /**
     * Get list of additional attributes which are not in default create/update list
     *
     * @access public
     * @return array
     * @author Ultimate Module Creator
     */
    public function getAdditionalAttributes()
    {
        $entity = Mage::getModel('eav/entity_type')->load('amazon_products_amazon', 'entity_type_code');
        $attributes = Mage::getResourceModel('eav/entity_attribute_collection')
            ->setEntityTypeFilter($entity->getEntityTypeId());
        $result = array();
        foreach ($attributes as $attribute) {
            if (!in_array($attribute->getAttributeCode(), $this->_defaultAttributeList)) {
                if ($attribute->getIsGlobal() == Amazon_Products_Model_Attribute::SCOPE_GLOBAL) {
                    $scope = 'global';
                } elseif ($attribute->getIsGlobal() == Amazon_Products_Model_Attribute::SCOPE_WEBSITE) {
                    $scope = 'website';
                } else {
                    $scope = 'store';
                }

                $result[] = array(
                    'attribute_id' => $attribute->getId(),
                    'code'         => $attribute->getAttributeCode(),
                    'type'         => $attribute->getFrontendInput(),
                    'required'     => $attribute->getIsRequired(),
                    'scope'        => $scope
                );
            }
        }

        return $result;
    }

    /**
     * get data for api
     *
     * @access protected
     * @param Amazon_Products_Model_Amazon $amazon
     * @return array()
     * @author Ultimate Module Creator
     */
    protected function _getApiData(Amazon_Products_Model_Amazon $amazon)
    {
        $data = array();
        $additional = array();
        $additionalAttributes = $this->getAdditionalAttributes();
        $additionalByCode = array();
        foreach ($additionalAttributes as $attribute) {
            $additionalByCode[] = $attribute['code'];
        }
        foreach ($amazon->getData() as $key=>$value) {
            if (!in_array($key, $additionalByCode)) {
                $data[$key] = $value;
            } else {
                $additional[] = array('key'=>$key, 'value'=>$value);
            }
        }
        if (!empty($additional)) {
            $data['additional_attributes'] = $additional;
        }
        return $data;
    }
}

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
class amazonProducts_AmazonProducts_Model_Amazonproduct_Api extends Mage_Api_Model_Resource_Abstract
{


    /**
     * init amazon product
     *
     * @access protected
     * @param $amazonproductId
     * @return amazonProducts_AmazonProducts_Model_Amazonproduct
     * @author      Ultimate Module Creator
     */
    protected function _initAmazonproduct($amazonproductId)
    {
        $amazonproduct = Mage::getModel('amazonproducts_amazonproducts/amazonproduct')->load($amazonproductId);
        if (!$amazonproduct->getId()) {
            $this->_fault('amazonproduct_not_exists');
        }
        return $amazonproduct;
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
        $collection = Mage::getModel('amazonproducts_amazonproducts/amazonproduct')->getCollection()
            ->addFieldToFilter(
                'entity_id',
                array(
                    'neq'=>Mage::helper('amazonproducts_amazonproducts/amazonproduct')->getRootAmazonproductId()
                )
            );
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
        foreach ($collection as $amazonproduct) {
            $result[] = $this->_getApiData($amazonproduct);
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
                throw new Exception(Mage::helper('amazonproducts_amazonproducts')->__("Data cannot be null"));
            }
            $data = (array)$data;
            $amazonproduct = Mage::getModel('amazonproducts_amazonproducts/amazonproduct')
                ->setData((array)$data)
                ->save();
        } catch (Mage_Core_Exception $e) {
            $this->_fault('data_invalid', $e->getMessage());
        } catch (Exception $e) {
            $this->_fault('data_invalid', $e->getMessage());
        }
        return $amazonproduct->getId();
    }

    /**
     * Change existing amazon product information
     *
     * @access public
     * @param int $amazonproductId
     * @param array $data
     * @return bool
     * @author Ultimate Module Creator
     */
    public function update($amazonproductId, $data)
    {
        $amazonproduct = $this->_initAmazonproduct($amazonproductId);
        try {
            $data = (array)$data;
            $amazonproduct->addData($data);
            $amazonproduct->save();
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
     * @param int $amazonproductId
     * @return bool
     * @author Ultimate Module Creator
     */
    public function remove($amazonproductId)
    {
        $amazonproduct = $this->_initAmazonproduct($amazonproductId);
        try {
            $amazonproduct->delete();
        } catch (Mage_Core_Exception $e) {
            $this->_fault('remove_error', $e->getMessage());
        }
        return true;
    }

    /**
     * get info
     *
     * @access public
     * @param int $amazonproductId
     * @return array
     * @author Ultimate Module Creator
     */
    public function info($amazonproductId)
    {
        $result = array();
        $amazonproduct = $this->_initAmazonproduct($amazonproductId);
        $result = $this->_getApiData($amazonproduct);
        //related products
        $result['products'] = array();
        $relatedProductsCollection = $amazonproduct->getSelectedProductsCollection();
        foreach ($relatedProductsCollection as $product) {
            $result['products'][$product->getId()] = $product->getPosition();
        }
        return $result;
    }

    /**
     * Move amazon product in tree
     *
     * @param int $amazonproductId
     * @param int $parentId
     * @param int $afterId
     * @return boolean
     */
    public function move($amazonproductId, $parentId, $afterId = null)
    {
        $amazonproduct = $this->_initAmazonproduct($amazonproductId);
        $parentAmazonproduct = $this->_initAmazonproduct($parentId);
        if ($afterId === null && $parentAmazonproduct->hasChildren()) {
            $parentChildren = $parentAmazonproduct->getChildAmazonproducts();
            $afterId = array_pop(explode(',', $parentChildren));
        }
        if ( strpos($parentAmazonproduct->getPath(), $amazonproduct->getPath()) === 0) {
            $this->_fault(
                'not_moved',
                Mage::helper('amazonproducts_amazonproducts')->__("Cannot move parent inside amazon product")
            );
        }
        try {
            $amazonproduct->move($parentId, $afterId);
        } catch (Mage_Core_Exception $e) {
            $this->_fault('not_moved', $e->getMessage());
        }
        return true;
    }
    /**
     * Assign product to amazon product
     *
     * @access public
     * @param int $amazonproductId
     * @param int $productId
     * @param int $position
     * @return boolean
     * @author Ultimate Module Creator
     */
    public function assignProduct($amazonproductId, $productId, $position = null)
    {
        $amazonproduct = $this->_initAmazonproduct($amazonproductId);
        $positions    = array();
        $products     = $amazonproduct->getSelectedProducts();
        foreach ($products as $product) {
            $positions[$product->getId()] = array('position'=>$product->getPosition());
        }
        $product = Mage::getModel('catalog/product')->load($productId);
        if (!$product->getId()) {
            $this->_fault('product_not_exists');
        }
        $positions[$productId]['position'] = $position;
        $amazonproduct->setProductsData($positions);
        try {
            $amazonproduct->save();
        } catch (Mage_Core_Exception $e) {
            $this->_fault('data_invalid', $e->getMessage());
        }
        return true;
    }

    /**
     * remove product from amazon product
     *
     * @access public
     * @param int $amazonproductId
     * @param int $productId
     * @return boolean
     * @author Ultimate Module Creator
     */
    public function unassignProduct($amazonproductId, $productId)
    {
        $amazonproduct = $this->_initAmazonproduct($amazonproductId);
        $positions    = array();
        $products     = $amazonproduct->getSelectedProducts();
        foreach ($products as $product) {
            $positions[$product->getId()] = array('position'=>$product->getPosition());
        }
        unset($positions[$productId]);
        $amazonproduct->setProductsData($positions);
        try {
            $amazonproduct->save();
        } catch (Mage_Core_Exception $e) {
            $this->_fault('data_invalid', $e->getMessage());
        }
        return true;
    }

    /**
     * get data for api
     *
     * @access protected
     * @param amazonProducts_AmazonProducts_Model_Amazonproduct $amazonproduct
     * @return array()
     * @author Ultimate Module Creator
     */
    protected function _getApiData(amazonProducts_AmazonProducts_Model_Amazonproduct $amazonproduct)
    {
        return $amazonproduct->getData();
    }
}

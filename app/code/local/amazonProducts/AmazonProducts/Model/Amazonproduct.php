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
 * Amazon Product model
 *
 * @category    amazonProducts
 * @package     amazonProducts_AmazonProducts
 * @author      Ultimate Module Creator
 */
class amazonProducts_AmazonProducts_Model_Amazonproduct extends Mage_Catalog_Model_Abstract
{
    /**
     * Entity code.
     * Can be used as part of method name for entity processing
     */
    const ENTITY    = 'amazonproducts_amazonproducts_amazonproduct';
    const CACHE_TAG = 'amazonproducts_amazonproducts_amazonproduct';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'amazonproducts_amazonproducts_amazonproduct';

    /**
     * Parameter name in event
     *
     * @var string
     */
    protected $_eventObject = 'amazonproduct';
    protected $_productInstance = null;

    /**
     * constructor
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function _construct()
    {
        parent::_construct();
        $this->_init('amazonproducts_amazonproducts/amazonproduct');
    }

    /**
     * before save amazon product
     *
     * @access protected
     * @return amazonProducts_AmazonProducts_Model_Amazonproduct
     * @author Ultimate Module Creator
     */
    protected function _beforeSave()
    {
        parent::_beforeSave();
        $now = Mage::getSingleton('core/date')->gmtDate();
        if ($this->isObjectNew()) {
            $this->setCreatedAt($now);
        }
        $this->setUpdatedAt($now);
        return $this;
    }

    /**
     * save amazon product relation
     *
     * @access public
     * @return amazonProducts_AmazonProducts_Model_Amazonproduct
     * @author Ultimate Module Creator
     */
    protected function _afterSave()
    {
        $this->getProductInstance()->saveAmazonproductRelation($this);
        return parent::_afterSave();
    }

    /**
     * get product relation model
     *
     * @access public
     * @return amazonProducts_AmazonProducts_Model_Amazonproduct_Product
     * @author Ultimate Module Creator
     */
    public function getProductInstance()
    {
        if (!$this->_productInstance) {
            $this->_productInstance = Mage::getSingleton('amazonproducts_amazonproducts/amazonproduct_product');
        }
        return $this->_productInstance;
    }

    /**
     * get selected products array
     *
     * @access public
     * @return array
     * @author Ultimate Module Creator
     */
    public function getSelectedProducts()
    {
        if (!$this->hasSelectedProducts()) {
            $products = array();
            foreach ($this->getSelectedProductsCollection() as $product) {
                $products[] = $product;
            }
            $this->setSelectedProducts($products);
        }
        return $this->getData('selected_products');
    }

    /**
     * Retrieve collection selected products
     *
     * @access public
     * @return amazonProducts_AmazonProducts_Resource_Amazonproduct_Product_Collection
     * @author Ultimate Module Creator
     */
    public function getSelectedProductsCollection()
    {
        $collection = $this->getProductInstance()->getProductCollection($this);
        return $collection;
    }

    /**
     * get the tree model
     *
     * @access public
     * @return amazonProducts_AmazonProducts_Model_Resource_Amazonproduct_Tree
     * @author Ultimate Module Creator
     */
    public function getTreeModel()
    {
        return Mage::getResourceModel('amazonproducts_amazonproducts/amazonproduct_tree');
    }

    /**
     * get tree model instance
     *
     * @access public
     * @return amazonProducts_AmazonProducts_Model_Resource_Amazonproduct_Tree
     * @author Ultimate Module Creator
     */
    public function getTreeModelInstance()
    {
        if (is_null($this->_treeModel)) {
            $this->_treeModel = Mage::getResourceSingleton('amazonproducts_amazonproducts/amazonproduct_tree');
        }
        return $this->_treeModel;
    }

    /**
     * Move amazon product
     *
     * @access public
     * @param   int $parentId new parent amazon product id
     * @param   int $afterAmazonproductId amazon product id after which we have put current amazon product
     * @return  amazonProducts_AmazonProducts_Model_Amazonproduct
     * @author Ultimate Module Creator
     */
    public function move($parentId, $afterAmazonproductId)
    {
        $parent = Mage::getModel('amazonproducts_amazonproducts/amazonproduct')->load($parentId);
        if (!$parent->getId()) {
            Mage::throwException(
                Mage::helper('amazonproducts_amazonproducts')->__(
                    'Amazon Product move operation is not possible: the new parent amazon product was not found.'
                )
            );
        }
        if (!$this->getId()) {
            Mage::throwException(
                Mage::helper('amazonproducts_amazonproducts')->__(
                    'Amazon Product move operation is not possible: the current amazon product was not found.'
                )
            );
        } elseif ($parent->getId() == $this->getId()) {
            Mage::throwException(
                Mage::helper('amazonproducts_amazonproducts')->__(
                    'Amazon Product move operation is not possible: parent amazon product is equal to child amazon product.'
                )
            );
        }
        $this->setMovedAmazonproductId($this->getId());
        $eventParams = array(
            $this->_eventObject => $this,
            'parent'            => $parent,
            'amazonproduct_id'     => $this->getId(),
            'prev_parent_id'    => $this->getParentId(),
            'parent_id'         => $parentId
        );
        $moveComplete = false;
        $this->_getResource()->beginTransaction();
        try {
            $this->getResource()->changeParent($this, $parent, $afterAmazonproductId);
            $this->_getResource()->commit();
            $this->setAffectedAmazonproductIds(array($this->getId(), $this->getParentId(), $parentId));
            $moveComplete = true;
        } catch (Exception $e) {
            $this->_getResource()->rollBack();
            throw $e;
        }
        if ($moveComplete) {
            Mage::app()->cleanCache(array(self::CACHE_TAG));
        }
        return $this;
    }

    /**
     * Get the parent amazon product
     *
     * @access public
     * @return  amazonProducts_AmazonProducts_Model_Amazonproduct
     * @author Ultimate Module Creator
     */
    public function getParentAmazonproduct()
    {
        if (!$this->hasData('parent_amazonproduct')) {
            $this->setData(
                'parent_amazonproduct',
                Mage::getModel('amazonproducts_amazonproducts/amazonproduct')->load($this->getParentId())
            );
        }
        return $this->_getData('parent_amazonproduct');
    }

    /**
     * Get the parent id
     *
     * @access public
     * @return  int
     * @author Ultimate Module Creator
     */
    public function getParentId()
    {
        $parentIds = $this->getParentIds();
        return intval(array_pop($parentIds));
    }

    /**
     * Get all parent amazon products ids
     *
     * @access public
     * @return array
     * @author Ultimate Module Creator
     */
    public function getParentIds()
    {
        return array_diff($this->getPathIds(), array($this->getId()));
    }

    /**
     * Get all amazon products children
     *
     * @access public
     * @param bool $asArray
     * @return mixed (array|string)
     * @author Ultimate Module Creator
     */
    public function getAllChildren($asArray = false)
    {
        $children = $this->getResource()->getAllChildren($this);
        if ($asArray) {
            return $children;
        } else {
            return implode(',', $children);
        }
    }

    /**
     * Get all amazon products children
     *
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getChildAmazonproducts()
    {
        return implode(',', $this->getResource()->getChildren($this, false));
    }

    /**
     * check the id
     *
     * @access public
     * @param int $id
     * @return bool
     * @author Ultimate Module Creator
     */
    public function checkId($id)
    {
        return $this->_getResource()->checkId($id);
    }

    /**
     * Get array amazon products ids which are part of amazon product path
     *
     * @access public
     * @return array
     * @author Ultimate Module Creator
     */
    public function getPathIds()
    {
        $ids = $this->getData('path_ids');
        if (is_null($ids)) {
            $ids = explode('/', $this->getPath());
            $this->setData('path_ids', $ids);
        }
        return $ids;
    }

    /**
     * Retrieve level
     *
     * @access public
     * @return int
     * @author Ultimate Module Creator
     */
    public function getLevel()
    {
        if (!$this->hasLevel()) {
            return count(explode('/', $this->getPath())) - 1;
        }
        return $this->getData('level');
    }

    /**
     * Verify amazon product ids
     *
     * @access public
     * @param array $ids
     * @return bool
     * @author Ultimate Module Creator
     */
    public function verifyIds(array $ids)
    {
        return $this->getResource()->verifyIds($ids);
    }

    /**
     * check if amazon product has children
     *
     * @access public
     * @return bool
     * @author Ultimate Module Creator
     */
    public function hasChildren()
    {
        return $this->_getResource()->getChildrenAmount($this) > 0;
    }

    /**
     * check if amazon product can be deleted
     *
     * @access protected
     * @return amazonProducts_AmazonProducts_Model_Amazonproduct
     * @author Ultimate Module Creator
     */
    protected function _beforeDelete()
    {
        if ($this->getResource()->isForbiddenToDelete($this->getId())) {
            Mage::throwException(Mage::helper('amazonproducts_amazonproducts')->__("Can't delete root amazon product."));
        }
        return parent::_beforeDelete();
    }

    /**
     * get the amazon products
     *
     * @access public
     * @param amazonProducts_AmazonProducts_Model_Amazonproduct $parent
     * @param int $recursionLevel
     * @param bool $sorted
     * @param bool $asCollection
     * @param bool $toLoad
     * @author Ultimate Module Creator
     */
    public function getAmazonproducts($parent, $recursionLevel = 0, $sorted=false, $asCollection=false, $toLoad=true)
    {
        return $this->getResource()->getAmazonproducts($parent, $recursionLevel, $sorted, $asCollection, $toLoad);
    }

    /**
     * Return parent amazon products of current amazon product
     *
     * @access public
     * @return array
     * @author Ultimate Module Creator
     */
    public function getParentAmazonproducts()
    {
        return $this->getResource()->getParentAmazonproducts($this);
    }

    /**
     * Return children amazon products of current amazon product
     *
     * @access public
     * @return array
     * @author Ultimate Module Creator
     */
    public function getChildrenAmazonproducts()
    {
        return $this->getResource()->getChildrenAmazonproducts($this);
    }

    /**
     * check if parents are enabled
     *
     * @access public
     * @return bool
     * @author Ultimate Module Creator
     */
    public function getStatusPath()
    {
        $parents = $this->getParentAmazonproducts();
        $rootId = Mage::helper('amazonproducts_amazonproducts/amazonproduct')->getRootAmazonproductId();
        foreach ($parents as $parent) {
            if ($parent->getId() == $rootId) {
                continue;
            }
            if (!$parent->getStatus()) {
                return false;
            }
        }
        return $this->getStatus();
    }

    /**
     * Retrieve default attribute set id
     *
     * @access public
     * @return int
     * @author Ultimate Module Creator
     */
    public function getDefaultAttributeSetId()
    {
        return $this->getResource()->getEntityType()->getDefaultAttributeSetId();
    }

    /**
     * get attribute text value
     *
     * @access public
     * @param $attributeCode
     * @return string
     * @author Ultimate Module Creator
     */
    public function getAttributeText($attributeCode)
    {
        $text = $this->getResource()
            ->getAttribute($attributeCode)
            ->getSource()
            ->getOptionText($this->getData($attributeCode));
        if (is_array($text)) {
            return implode(', ', $text);
        }
        return $text;
    }

    /**
     * get default values
     *
     * @access public
     * @return array
     * @author Ultimate Module Creator
     */
    public function getDefaultValues()
    {
        $values = array();
        $values['status'] = 1;
        return $values;
    }
    
}

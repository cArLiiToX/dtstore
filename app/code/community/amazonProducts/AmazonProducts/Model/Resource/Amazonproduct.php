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
 * Amazon Product resource model
 *
 * @category    amazonProducts
 * @package     amazonProducts_AmazonProducts
 * @author      Ultimate Module Creator
 */
class amazonProducts_AmazonProducts_Model_Resource_Amazonproduct extends Mage_Core_Model_Resource_Db_Abstract
{
    /**
     * Amazon Product tree object
     * @var Varien_Data_Tree_Db
     */
    protected $_tree;

    /**
     * constructor
     *
     * @access public
     * @author Ultimate Module Creator
     */
    public function _construct()
    {
        $this->_init('amazonproducts_amazonproducts/amazonproduct', 'entity_id');
    }

    /**
     * Get store ids to which specified item is assigned
     *
     * @access public
     * @param int $amazonproductId
     * @return array
     * @author Ultimate Module Creator
     */
    public function lookupStoreIds($amazonproductId)
    {
        $adapter = $this->_getReadAdapter();
        $select  = $adapter->select()
            ->from($this->getTable('amazonproducts_amazonproducts/amazonproduct_store'), 'store_id')
            ->where('amazonproduct_id = ?', (int)$amazonproductId);
        return $adapter->fetchCol($select);
    }

    /**
     * Perform operations after object load
     *
     * @access public
     * @param Mage_Core_Model_Abstract $object
     * @return amazonProducts_AmazonProducts_Model_Resource_Amazonproduct
     * @author Ultimate Module Creator
     */
    protected function _afterLoad(Mage_Core_Model_Abstract $object)
    {
        if ($object->getId()) {
            $stores = $this->lookupStoreIds($object->getId());
            $object->setData('store_id', $stores);
        }
        return parent::_afterLoad($object);
    }

    /**
     * Retrieve select object for load object data
     *
     * @param string $field
     * @param mixed $value
     * @param amazonProducts_AmazonProducts_Model_Amazonproduct $object
     * @return Zend_Db_Select
     */
    protected function _getLoadSelect($field, $value, $object)
    {
        $select = parent::_getLoadSelect($field, $value, $object);
        if ($object->getStoreId()) {
            $storeIds = array(Mage_Core_Model_App::ADMIN_STORE_ID, (int)$object->getStoreId());
            $select->join(
                array('amazonproducts_amazonproduct_store' => $this->getTable('amazonproducts_amazonproducts/amazonproduct_store')),
                $this->getMainTable() . '.entity_id = amazonproducts_amazonproduct_store.amazonproduct_id',
                array()
            )
            ->where('amazonproducts_amazonproduct_store.store_id IN (?)', $storeIds)
            ->order('amazonproducts_amazonproduct_store.store_id DESC')
            ->limit(1);
        }
        return $select;
    }

    /**
     * Retrieve amazon product tree object
     *
     * @access protected
     * @return Varien_Data_Tree_Db
     * @author Ultimate Module Creator
     */
    protected function _getTree()
    {
        if (!$this->_tree) {
            $this->_tree = Mage::getResourceModel('amazonproducts_amazonproducts/amazonproduct_tree')->load();
        }
        return $this->_tree;
    }

    /**
     * Process amazon product data before delete
     * update children count for parent amazon product
     * delete child amazon products
     *
     * @access protected
     * @param Varien_Object $object
     * @return amazonProducts_AmazonProducts_Model_Resource_Amazonproduct
     * @author Ultimate Module Creator
     */
    protected function _beforeDelete(Mage_Core_Model_Abstract $object)
    {
        parent::_beforeDelete($object);
        /**
         * Update children count for all parent amazon products
         */
        $parentIds = $object->getParentIds();
        if ($parentIds) {
            $childDecrease = $object->getChildrenCount() + 1; // +1 is itself
            $data = array('children_count' => new Zend_Db_Expr('children_count - ' . $childDecrease));
            $where = array('entity_id IN(?)' => $parentIds);
            $this->_getWriteAdapter()->update($this->getMainTable(), $data, $where);
        }
        $this->deleteChildren($object);
        return $this;
    }

    /**
     * Delete children amazon products of specific amazon product
     *
     * @access public
     * @param Varien_Object $object
     * @return amazonProducts_AmazonProducts_Model_Resource_Amazonproduct
     * @author Ultimate Module Creator
     */
    public function deleteChildren(Varien_Object $object)
    {
        $adapter = $this->_getWriteAdapter();
        $pathField = $adapter->quoteIdentifier('path');
        $select = $adapter->select()
            ->from($this->getMainTable(), array('entity_id'))
            ->where($pathField . ' LIKE :c_path');
        $childrenIds = $adapter->fetchCol($select, array('c_path' => $object->getPath() . '/%'));
        if (!empty($childrenIds)) {
            $adapter->delete(
                $this->getMainTable(),
                array('entity_id IN (?)' => $childrenIds)
            );
        }
        /**
         * Add deleted children ids to object
         * This data can be used in after delete event
         */
        $object->setDeletedChildrenIds($childrenIds);
        return $this;
    }

    /**
     * Process amazon product data after save amazon product object
     *
     * @access protected
     * @param Varien_Object $object
     * @return amazonProducts_AmazonProducts_Model_Resource_Amazonproduct
     * @author Ultimate Module Creator
     */
    protected function _afterSave(Mage_Core_Model_Abstract $object)
    {
        if (substr($object->getPath(), -1) == '/') {
            $object->setPath($object->getPath() . $object->getId());
            $this->_savePath($object);
        }


        $oldStores = $this->lookupStoreIds($object->getId());
        $newStores = (array)$object->getStores();
        if (empty($newStores)) {
            $newStores = (array)$object->getStoreId();
        }
        $table  = $this->getTable('amazonproducts_amazonproducts/amazonproduct_store');
        $insert = array_diff($newStores, $oldStores);
        $delete = array_diff($oldStores, $newStores);
        if ($delete) {
            $where = array(
                'amazonproduct_id = ?' => (int) $object->getId(),
                'store_id IN (?)' => $delete
            );
            $this->_getWriteAdapter()->delete($table, $where);
        }
        if ($insert) {
            $data = array();
            foreach ($insert as $storeId) {
                $data[] = array(
                    'amazonproduct_id'  => (int) $object->getId(),
                    'store_id' => (int) $storeId
                );
            }
            $this->_getWriteAdapter()->insertMultiple($table, $data);
        }
        return parent::_afterSave($object);
    }

    /**
     * Update path field
     *
     * @access protected
     * @param amazonProducts_AmazonProducts_Model_Amazonproduct $object
     * @return amazonProducts_AmazonProducts_Model_Resource_Amazonproduct
     * @author Ultimate Module Creator
     */
    protected function _savePath($object)
    {
        if ($object->getId()) {
            $this->_getWriteAdapter()->update(
                $this->getMainTable(),
                array('path' => $object->getPath()),
                array('entity_id = ?' => $object->getId())
            );
        }
        return $this;
    }

    /**
     * Get maximum position of child amazon products by specific tree path
     *
     * @access protected
     * @param string $path
     * @return int
     * @author Ultimate Module Creator
     */
    protected function _getMaxPosition($path)
    {
        $adapter = $this->getReadConnection();
        $positionField = $adapter->quoteIdentifier('position');
        $level   = count(explode('/', $path));
        $bind = array(
            'c_level' => $level,
            'c_path'  => $path . '/%'
        );
        $select  = $adapter->select()
            ->from($this->getMainTable(), 'MAX(' . $positionField . ')')
            ->where($adapter->quoteIdentifier('path') . ' LIKE :c_path')
            ->where($adapter->quoteIdentifier('level') . ' = :c_level');

        $position = $adapter->fetchOne($select, $bind);
        if (!$position) {
            $position = 0;
        }
        return $position;
    }

    /**
     * Get children amazon products count
     *
     * @access public
     * @param int $amazonproductId
     * @return int
     * @author Ultimate Module Creator
     */
    public function getChildrenCount($amazonproductId)
    {
        $select = $this->_getReadAdapter()->select()
            ->from($this->getMainTable(), 'children_count')
            ->where('entity_id = :entity_id');
        $bind = array('entity_id' => $amazonproductId);
        return $this->_getReadAdapter()->fetchOne($select, $bind);
    }

    /**
     * Check if amazon product id exist
     *
     * @access public
     * @param int $entityId
     * @return bool
     * @author Ultimate Module Creator
     */
    public function checkId($entityId)
    {
        $select = $this->_getReadAdapter()->select()
            ->from($this->getMainTable(), 'entity_id')
            ->where('entity_id = :entity_id');
        $bind =  array('entity_id' => $entityId);
        return $this->_getReadAdapter()->fetchOne($select, $bind);
    }

    /**
     * Check array of amazon products identifiers
     *
     * @access public
     * @param array $ids
     * @return array
     * @author Ultimate Module Creator
     */
    public function verifyIds(array $ids)
    {
        if (empty($ids)) {
            return array();
        }
        $select = $this->_getReadAdapter()->select()
            ->from($this->getMainTable(), 'entity_id')
            ->where('entity_id IN(?)', $ids);

        return $this->_getReadAdapter()->fetchCol($select);
    }

    /**
     * Get count of active/not active children amazon products
     *
     * @param amazonProducts_AmazonProducts_Model_Amazonproduct $amazonproduct
     * @param bool $isActiveFlag
     * @return int
     * @author Ultimate Module Creator
     */
    public function getChildrenAmount($amazonproduct, $isActiveFlag = true)
    {
        $bind = array(
            'active_flag'  => $isActiveFlag,
            'c_path'   => $amazonproduct->getPath() . '/%'
        );
        $select = $this->_getReadAdapter()->select()
            ->from(array('m' => $this->getMainTable()), array('COUNT(m.entity_id)'))
            ->where('m.path LIKE :c_path')
            ->where('status' . ' = :active_flag');
        return $this->_getReadAdapter()->fetchOne($select, $bind);
    }

    /**
     * Return parent amazon products of amazon product
     *
     * @access public
     * @param amazonProducts_AmazonProducts_Model_Amazonproduct $amazonproduct
     * @return array
     * @author Ultimate Module Creator
     */
    public function getParentAmazonproducts($amazonproduct)
    {
        $pathIds = array_reverse(explode('/', $amazonproduct->getPath()));
        $amazonproducts = Mage::getResourceModel('amazonproducts_amazonproducts/amazonproduct_collection')
            ->addFieldToFilter('entity_id', array('in' => $pathIds))
            ->load()
            ->getItems();
        return $amazonproducts;
    }

    /**
     * Return child amazon products
     *
     * @access public
     * @param amazonProducts_AmazonProducts_Model_Amazonproduct $amazonproduct
     * @return amazonProducts_AmazonProducts_Model_Resource_Amazonproduct_Collection
     * @author Ultimate Module Creator
     */
    public function getChildrenAmazonproducts($amazonproduct)
    {
        $collection = $amazonproduct->getCollection();
        $collection
            ->addIdFilter($amazonproduct->getChildAmazonproducts())
            ->setOrder('position', Varien_Db_Select::SQL_ASC)
            ->load();
        return $collection;
    }
    /**
     * Return children ids of amazon product
     *
     * @access public
     * @param amazonProducts_AmazonProducts_Model_Amazonproduct $amazonproduct
     * @param boolean $recursive
     * @return array
     * @author Ultimate Module Creator
     */
    public function getChildren($amazonproduct, $recursive = true)
    {
        $bind = array(
            'c_path'   => $amazonproduct->getPath() . '/%'
        );
        $select = $this->_getReadAdapter()->select()
            ->from(array('m' => $this->getMainTable()), 'entity_id')
            ->where('status = ?', 1)
            ->where($this->_getReadAdapter()->quoteIdentifier('path') . ' LIKE :c_path');
        if (!$recursive) {
            $select->where($this->_getReadAdapter()->quoteIdentifier('level') . ' <= :c_level');
            $bind['c_level'] = $amazonproduct->getLevel() + 1;
        }
        return $this->_getReadAdapter()->fetchCol($select, $bind);
    }

    /**
     * Process amazon product data before saving
     * prepare path and increment children count for parent amazon products
     *
     * @access protected
     * @param Varien_Object $object
     * @return amazonProducts_AmazonProducts_Model_Resource_Amazonproduct
     * @author Ultimate Module Creator
     */
    protected function _beforeSave(Mage_Core_Model_Abstract $object)
    {
        parent::_beforeSave($object);
        if (!$object->getChildrenCount()) {
            $object->setChildrenCount(0);
        }
        if ($object->getLevel() === null) {
            $object->setLevel(1);
        }
        if (!$object->getId() && !$object->getInitialSetupFlag()) {
            $object->setPosition($this->_getMaxPosition($object->getPath()) + 1);
            $path  = explode('/', $object->getPath());
            $level = count($path);
            $object->setLevel($level);
            if ($level) {
                $object->setParentId($path[$level - 1]);
            }
            $object->setPath($object->getPath() . '/');
            $toUpdateChild = explode('/', $object->getPath());
            $this->_getWriteAdapter()->update(
                $this->getMainTable(),
                array('children_count'  => new Zend_Db_Expr('children_count+1')),
                array('entity_id IN(?)' => $toUpdateChild)
            );
        }
        return $this;
    }

    /**
     * Retrieve amazon products
     *
     * @access public
     * @param integer $parent
     * @param integer $recursionLevel
     * @param boolean|string $sorted
     * @param boolean $asCollection
     * @param boolean $toLoad
     * @return Varien_Data_Tree_Node_Collection|amazonProducts_AmazonProducts_Model_Resource_Amazonproduct_Collection
     * @author Ultimate Module Creator
     */
    public function getAmazonproducts(
        $parent,
        $recursionLevel = 0,
        $sorted = false,
        $asCollection = false,
        $toLoad = true
    )
    {
        $tree = Mage::getResourceModel('amazonproducts_amazonproducts/amazonproduct_tree');
        $nodes = $tree->loadNode($parent)
            ->loadChildren($recursionLevel)
            ->getChildren();
        $tree->addCollectionData(null, $sorted, $parent, $toLoad, true);
        if ($asCollection) {
            return $tree->getCollection();
        }
        return $nodes;
    }

    /**
     * Return all children ids of amazonproduct (with amazonproduct id)
     *
     * @access public
     * @param amazonProducts_AmazonProducts_Model_Amazonproduct $amazonproduct
     * @return array
     * @author Ultimate Module Creator
     */
    public function getAllChildren($amazonproduct)
    {
        $children = $this->getChildren($amazonproduct);
        $myId = array($amazonproduct->getId());
        $children = array_merge($myId, $children);
        return $children;
    }

    /**
     * Check amazon product is forbidden to delete.
     *
     * @access public
     * @param integer $amazonproductId
     * @return boolean
     * @author Ultimate Module Creator
     */
    public function isForbiddenToDelete($amazonproductId)
    {
        return ($amazonproductId == Mage::helper('amazonproducts_amazonproducts/amazonproduct')->getRootAmazonproductId());
    }

    /**
     * Get amazon product path value by its id
     *
     * @access public
     * @param int $amazonproductId
     * @return string
     * @author Ultimate Module Creator
     */
    public function getAmazonproductPathById($amazonproductId)
    {
        $select = $this->getReadConnection()->select()
            ->from($this->getMainTable(), array('path'))
            ->where('entity_id = :entity_id');
        $bind = array('entity_id' => (int)$amazonproductId);
        return $this->getReadConnection()->fetchOne($select, $bind);
    }

    /**
     * Move amazon product to another parent node
     *
     * @access public
     * @param amazonProducts_AmazonProducts_Model_Amazonproduct $amazonproduct
     * @param amazonProducts_AmazonProducts_Model_Amazonproduct $newParent
     * @param null|int $afterAmazonproductId
     * @return amazonProducts_AmazonProducts_Model_Resource_Amazonproduct
     * @author Ultimate Module Creator
     */
    public function changeParent(
        amazonProducts_AmazonProducts_Model_Amazonproduct $amazonproduct,
        amazonProducts_AmazonProducts_Model_Amazonproduct $newParent,
        $afterAmazonproductId = null
    )
    {
        $childrenCount  = $this->getChildrenCount($amazonproduct->getId()) + 1;
        $table          = $this->getMainTable();
        $adapter        = $this->_getWriteAdapter();
        $levelFiled     = $adapter->quoteIdentifier('level');
        $pathField      = $adapter->quoteIdentifier('path');

        /**
         * Decrease children count for all old amazon product parent amazon products
         */
        $adapter->update(
            $table,
            array('children_count' => new Zend_Db_Expr('children_count - ' . $childrenCount)),
            array('entity_id IN(?)' => $amazonproduct->getParentIds())
        );
        /**
         * Increase children count for new amazon product parents
         */
        $adapter->update(
            $table,
            array('children_count' => new Zend_Db_Expr('children_count + ' . $childrenCount)),
            array('entity_id IN(?)' => $newParent->getPathIds())
        );

        $position = $this->_processPositions($amazonproduct, $newParent, $afterAmazonproductId);

        $newPath  = sprintf('%s/%s', $newParent->getPath(), $amazonproduct->getId());
        $newLevel = $newParent->getLevel() + 1;
        $levelDisposition = $newLevel - $amazonproduct->getLevel();

        /**
         * Update children nodes path
         */
        $adapter->update(
            $table,
            array(
                'path' => new Zend_Db_Expr(
                    'REPLACE(' . $pathField . ','.
                    $adapter->quote($amazonproduct->getPath() . '/'). ', '.$adapter->quote($newPath . '/').')'
                ),
                'level' => new Zend_Db_Expr($levelFiled . ' + ' . $levelDisposition)
            ),
            array($pathField . ' LIKE ?' => $amazonproduct->getPath() . '/%')
        );
        /**
         * Update moved amazon product data
         */
        $data = array(
            'path'  => $newPath,
            'level' => $newLevel,
            'position'  =>$position,
            'parent_id' =>$newParent->getId()
        );
        $adapter->update($table, $data, array('entity_id = ?' => $amazonproduct->getId()));
        // Update amazon product object to new data
        $amazonproduct->addData($data);
        return $this;
    }

    /**
     * Process positions of old parent amazon product children and new parent amazon product children.
     * Get position for moved amazon product
     *
     * @access protected
     * @param amazonProducts_AmazonProducts_Model_Amazonproduct $amazonproduct
     * @param amazonProducts_AmazonProducts_Model_Amazonproduct $newParent
     * @param null|int $afterAmazonproductId
     * @return int
     * @author Ultimate Module Creator
     */
    protected function _processPositions($amazonproduct, $newParent, $afterAmazonproductId)
    {
        $table  = $this->getMainTable();
        $adapter= $this->_getWriteAdapter();
        $positionField  = $adapter->quoteIdentifier('position');

        $bind = array(
            'position' => new Zend_Db_Expr($positionField . ' - 1')
        );
        $where = array(
            'parent_id = ?' => $amazonproduct->getParentId(),
            $positionField . ' > ?' => $amazonproduct->getPosition()
        );
        $adapter->update($table, $bind, $where);

        /**
         * Prepare position value
         */
        if ($afterAmazonproductId) {
            $select = $adapter->select()
                ->from($table, 'position')
                ->where('entity_id = :entity_id');
            $position = $adapter->fetchOne($select, array('entity_id' => $afterAmazonproductId));
            $bind = array(
                'position' => new Zend_Db_Expr($positionField . ' + 1')
            );
            $where = array(
                'parent_id = ?' => $newParent->getId(),
                $positionField . ' > ?' => $position
            );
            $adapter->update($table, $bind, $where);
        } elseif ($afterAmazonproductId !== null) {
            $position = 0;
            $bind = array(
                'position' => new Zend_Db_Expr($positionField . ' + 1')
            );
            $where = array(
                'parent_id = ?' => $newParent->getId(),
                $positionField . ' > ?' => $position
            );
            $adapter->update($table, $bind, $where);
        } else {
            $select = $adapter->select()
                ->from($table, array('position' => new Zend_Db_Expr('MIN(' . $positionField. ')')))
                ->where('parent_id = :parent_id');
            $position = $adapter->fetchOne($select, array('parent_id' => $newParent->getId()));
        }
        $position += 1;
        return $position;
    }
}

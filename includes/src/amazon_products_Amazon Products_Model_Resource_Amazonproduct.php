<?php
/**
 * amazon_products_Amazon Products extension
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 * 
 * @category       amazon_products
 * @package        amazon_products_Amazon Products
 * @copyright      Copyright (c) 2014
 * @license        http://opensource.org/licenses/mit-license.php MIT License
 */
/**
 * Amazon Product resource model
 *
 * @category    amazon_products
 * @package     amazon_products_Amazon Products
 * @author      Ultimate Module Creator
 */
class amazon_products_Amazon Products_Model_Resource_Amazonproduct extends Mage_Catalog_Model_Resource_Abstract
{
    /**
     * Amazon Product tree object
     * @var Varien_Data_Tree_Db
     */
    protected $_tree;
    protected $_amazonproductProductTable = null;


    /**
     * constructor
     *
     * @access public
     * @author Ultimate Module Creator
     */
    public function __construct()
    {
        $resource = Mage::getSingleton('core/resource');
        $this->setType('amazon_products_amazon products_amazonproduct')
            ->setConnection(
                $resource->getConnection('amazonproduct_read'),
                $resource->getConnection('amazonproduct_write')
            );
        $this->_amazonproductProductTable = $this->getTable('amazon_products_amazon products/amazonproduct_product');

    }

    /**
     * wrapper for main table getter
     *
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getMainTable()
    {
        return $this->getEntityTable();
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
            $this->_tree = Mage::getResourceModel('amazon_products_amazon products/amazonproduct_tree')->load();
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
     * @return amazon_products_Amazon Products_Model_Resource_Amazonproduct
     * @author Ultimate Module Creator
     */
    protected function _beforeDelete(Varien_Object $object)
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
     * @return amazon_products_Amazon Products_Model_Resource_Amazonproduct
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
     * @return amazon_products_Amazon Products_Model_Resource_Amazonproduct
     * @author Ultimate Module Creator
     */
    protected function _afterSave(Varien_Object $object)
    {
        if (substr($object->getPath(), -1) == '/') {
            $object->setPath($object->getPath() . $object->getId());
            $this->_savePath($object);
        }
        return parent::_afterSave($object);
    }

    /**
     * Update path field
     *
     * @access protected
     * @param amazon_products_Amazon Products_Model_Amazonproduct $object
     * @return amazon_products_Amazon Products_Model_Resource_Amazonproduct
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
     * @param amazon_products_Amazon Products_Model_Amazonproduct $amazonproduct
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
     * @param amazon_products_Amazon Products_Model_Amazonproduct $amazonproduct
     * @return array
     * @author Ultimate Module Creator
     */
    public function getParentAmazonproducts($amazonproduct)
    {
        $pathIds = array_reverse(explode('/', $amazonproduct->getPath()));
        $amazonproducts = Mage::getResourceModel('amazon_products_amazon products/amazonproduct_collection')
            ->addAttributeToFilter('entity_id', array('in' => $pathIds))
            ->addAttributeToSelect('*');
        return $amazonproducts;
    }

    /**
     * Return child amazon products
     *
     * @access public
     * @param amazon_products_Amazon Products_Model_Amazonproduct $amazonproduct
     * @return amazon_products_Amazon Products_Model_Resource_Amazonproduct_Collection
     * @author Ultimate Module Creator
     */
    public function getChildrenAmazonproducts($amazonproduct)
    {
        $collection = $amazonproduct->getCollection();
        $collection
            ->addAttributeToFilter('status', 1)
            ->addIdFilter($amazonproduct->getChildAmazonproducts())
            ->setOrder('position', Varien_Db_Select::SQL_ASC);
        return $collection;
    }

    /**
     * Return children ids of amazon product
     *
     * @access public
     * @param amazon_products_Amazon Products_Model_Amazonproduct $amazonproduct
     * @param boolean $recursive
     * @return array
     * @author Ultimate Module Creator
     */
    public function getChildren($amazonproduct, $recursive = true)
    {
        $attributeId  = (int)$this->_getStatusAttributeId();
        $backendTable = $this->getTable(array($this->getEntityTablePrefix(), 'int'));
        $adapter      = $this->_getReadAdapter();
        $checkSql     = $adapter->getCheckSql('c.value_id > 0', 'c.value', 'd.value');
        $bind = array(
            'attribute_id' => $attributeId,
            'store_id'     => $amazonproduct->getStoreId(),
            'scope'        => 1,
            'c_path'       => $amazonproduct->getPath() . '/%'
        );
        $select = $this->_getReadAdapter()->select()
            ->from(array('m' => $this->getEntityTable()), 'entity_id')
            ->joinLeft(
                array('d' => $backendTable),
                'd.attribute_id = :attribute_id AND d.store_id = 0 AND d.entity_id = m.entity_id',
                array()
            )
            ->joinLeft(
                array('c' => $backendTable),
                'c.attribute_id = :attribute_id AND c.store_id = :store_id AND c.entity_id = m.entity_id',
                array()
            )
            ->where($checkSql . ' = :scope')
            ->where($adapter->quoteIdentifier('path') . ' LIKE :c_path');
        if (!$recursive) {
            $select->where($adapter->quoteIdentifier('level') . ' <= :c_level');
            $bind['c_level'] = $amazonproduct->getLevel() + 1;
        }

        return $adapter->fetchCol($select, $bind);
    }

    protected $_statusAttributeId = null;

    /**
     * Get "is_active" attribute identifier
     *
     * @access protected
     * @return int
     * @author Ultimate Module Creator
     */
    protected function _getStatusAttributeId()
    {
        if ($this->_statusAttributeId === null) {
            $bind = array(
                'amazon_products_amazon products_amazonproduct' => amazon_products_Amazon Products_Model_Amazonproduct::ENTITY,
                'status'        => 'status',
            );
            $select = $this->_getReadAdapter()->select()
                ->from(array('a'=>$this->getTable('eav/attribute')), array('attribute_id'))
                ->join(array('t'=>$this->getTable('eav/entity_type')), 'a.entity_type_id = t.entity_type_id')
                ->where('entity_type_code = :amazon_products_amazon products_amazonproduct')
                ->where('attribute_code = :status');

            $this->_statusAttributeId = $this->_getReadAdapter()->fetchOne($select, $bind);
        }
        return $this->_statusAttributeId;
    }

    /**
     * Process amazon product data before saving
     * prepare path and increment children count for parent amazon products
     *
     * @access protected
     * @param Varien_Object $object
     * @return amazon_products_Amazon Products_Model_Resource_Amazonproduct
     * @author Ultimate Module Creator
     */
    protected function _beforeSave(Varien_Object $object)
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
     * @return Varien_Data_Tree_Node_Collection|amazon_products_Amazon Products_Model_Resource_Amazonproduct_Collection
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
        $tree = Mage::getResourceModel('amazon_products_amazon products/amazonproduct_tree');
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
     * @param amazon_products_Amazon Products_Model_Amazonproduct $amazonproduct
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
        return ($amazonproductId == Mage::helper('amazon_products_amazon products/amazonproduct')->getRootAmazonproductId());
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
     * @param amazon_products_Amazon Products_Model_Amazonproduct $amazonproduct
     * @param amazon_products_Amazon Products_Model_Amazonproduct $newParent
     * @param null|int $afterAmazonproductId
     * @return amazon_products_Amazon Products_Model_Resource_Amazonproduct
     * @author Ultimate Module Creator
     */
    public function changeParent(
        amazon_products_Amazon Products_Model_Amazonproduct $amazonproduct,
        amazon_products_Amazon Products_Model_Amazonproduct $newParent,
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
     * @param amazon_products_Amazon Products_Model_Amazonproduct $amazonproduct
     * @param amazon_products_Amazon Products_Model_Amazonproduct $newParent
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

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
 * Amazon Product tree resource model
 *
 * @category    amazon_products
 * @package     amazon_products_Amazon Products
 * @author      Ultimate Module Creator
 */
class amazon_products_Amazon Products_Model_Resource_Amazonproduct_Tree extends Varien_Data_Tree_Dbp
{
    const ID_FIELD        = 'entity_id';
    const PATH_FIELD      = 'path';
    const ORDER_FIELD     = 'order';
    const LEVEL_FIELD     = 'level';

    /**
     * Amazon Products resource collection
     *
     * @var amazon_products_Amazon Products_Model_Resource_Amazonproduct_Collection
     */
    protected $_collection;
    protected $_storeId;

    /**
     * Inactive amazon products ids
     * @var array
     */

    protected $_inactiveAmazonproductIds  = null;

    /**
     * Initialize tree
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function __construct()
    {
        $resource = Mage::getSingleton('core/resource');
        parent::__construct(
            $resource->getConnection('amazon_products_amazon products_write'),
            $resource->getTableName('amazon_products_amazon products/amazonproduct'),
            array(
                Varien_Data_Tree_Dbp::ID_FIELD    => 'entity_id',
                Varien_Data_Tree_Dbp::PATH_FIELD  => 'path',
                Varien_Data_Tree_Dbp::ORDER_FIELD => 'position',
                Varien_Data_Tree_Dbp::LEVEL_FIELD => 'level',
            )
        );
    }

    /**
     * Get amazon products collection
     *
     * @access public
     * @param boolean $sorted
     * @return amazon_products_Amazon Products_Model_Resource_Amazonproduct_Collection
     * @author Ultimate Module Creator
     */
    public function getCollection($sorted = false)
    {
        if (is_null($this->_collection)) {
            $this->_collection = $this->_getDefaultCollection($sorted);
        }
        return $this->_collection;
    }
    /**
     * set the collection
     *
     * @access public
     * @param amazon_products_Amazon Products_Model_Resource_Amazonproduct_Collection $collection
     * @return amazon_products_Amazon Products_Model_Resource_Amazonproduct_Tree
     */
    public function setCollection($collection)
    {
        if (!is_null($this->_collection)) {
            destruct($this->_collection);
        }
        $this->_collection = $collection;
        return $this;
    }
    /**
     * get the default collection
     *
     * @access protected
     * @param boolean $sorted
     * @return amazon_products_Amazon Products_Model_Resource_Amazonproduct_Collection
     */
    protected function _getDefaultCollection($sorted = false)
    {
        $collection = Mage::getModel('amazon_products_amazon products/amazonproduct')->getCollection();
        $collection->addAttributeToSelect('name');
        if ($sorted) {
            if (is_string($sorted)) {
                $collection->setOrder($sorted);
            } else {
                $collection->setOrder('name');
            }
        }
        return $collection;
    }

    /**
     * Executing parents move method and cleaning cache after it
     *
     * @access public
     * @param unknown_type $amazonproduct
     * @param unknown_type $newParent
     * @param unknown_type $prevNode
     * @author Ultimate Module Creator
     */
    public function move($amazonproduct, $newParent, $prevNode = null)
    {
        Mage::getResourceSingleton('amazon_products_amazon products/amazonproduct')
            ->move($amazonproduct->getId(), $newParent->getId());
        parent::move($amazonproduct, $newParent, $prevNode);
        $this->_afterMove($amazonproduct, $newParent, $prevNode);
    }

    /**
     * Move tree after
     *
     * @access protected
     * @param unknown_type $amazonproduct
     * @param Varien_Data_Tree_Node $newParent
     * @param Varien_Data_Tree_Node $prevNode
     * @return amazon_products_Amazon Products_Model_Resource_Amazonproduct_Tree
     */
    protected function _afterMove($amazonproduct, $newParent, $prevNode)
    {
        Mage::app()->cleanCache(array(amazon_products_Amazon Products_Model_Amazonproduct::CACHE_TAG));
        return $this;
    }

    /**
     * Load whole amazon product tree, that will include specified amazon products ids.
     *
     * @access public
     * @param array $ids
     * @param bool $addCollectionData
     * @return amazon_products_Amazon Products_Model_Resource_Amazonproduct_Tree
     * @author Ultimate Module Creator
     */
    public function loadByIds($ids, $addCollectionData = true)
    {
        $levelField = $this->_conn->quoteIdentifier('level');
        $pathField  = $this->_conn->quoteIdentifier('path');
        // load first two levels, if no ids specified
        if (empty($ids)) {
            $select = $this->_conn->select()
                ->from($this->_table, 'entity_id')
                ->where($levelField . ' <= 2');
            $ids = $this->_conn->fetchCol($select);
        }
        if (!is_array($ids)) {
            $ids = array($ids);
        }
        foreach ($ids as $key => $id) {
            $ids[$key] = (int)$id;
        }
        // collect paths of specified IDs and prepare to collect all their parents and neighbours
        $select = $this->_conn->select()
            ->from($this->_table, array('path', 'level'))
            ->where('entity_id IN (?)', $ids);
        $where = array($levelField . '=0' => true);

        foreach ($this->_conn->fetchAll($select) as $item) {
            $pathIds  = explode('/', $item['path']);
            $level = (int)$item['level'];
            while ($level > 0) {
                $pathIds[count($pathIds) - 1] = '%';
                $path = implode('/', $pathIds);
                $where["$levelField=$level AND $pathField LIKE '$path'"] = true;
                array_pop($pathIds);
                $level--;
            }
        }
        $where = array_keys($where);

        // get all required records
        if ($addCollectionData) {
            $select = $this->_createCollectionDataSelect();
        } else {
            $select = clone $this->_select;
            $select->order($this->_orderField . ' ' . Varien_Db_Select::SQL_ASC);
        }
        $select->where(implode(' OR ', $where));

        // get array of records and add them as nodes to the tree
        $arrNodes = $this->_conn->fetchAll($select);
        if (!$arrNodes) {
            return false;
        }
        $childrenItems = array();
        foreach ($arrNodes as $key => $nodeInfo) {
            $pathToParent = explode('/', $nodeInfo[$this->_pathField]);
            array_pop($pathToParent);
            $pathToParent = implode('/', $pathToParent);
            $childrenItems[$pathToParent][] = $nodeInfo;
        }
        $this->addChildNodes($childrenItems, '', null);
        return $this;
    }

    /**
     * Load array of amazon product parents
     *
     * @access public
     * @param string $path
     * @param bool $addCollectionData
     * @param bool $withRootNode
     * @return array
     * @author Ultimate Module Creator
     */
    public function loadBreadcrumbsArray($path, $addCollectionData = true, $withRootNode = false)
    {
        $pathIds = explode('/', $path);
        if (!$withRootNode) {
            array_shift($pathIds);
        }
        $result = array();
        if (!empty($pathIds)) {
            if ($addCollectionData) {
                $select = $this->_createCollectionDataSelect(false);
            } else {
                $select = clone $this->_select;
            }
            $select
                ->where('e.entity_id IN(?)', $pathIds)
                ->order($this->_conn->getLengthSql('e.path') . ' ' . Varien_Db_Select::SQL_ASC);
            $result = $this->_conn->fetchAll($select);
        }
        return $result;
    }

    /**
     * Obtain select for amazon products with attributes.
     * By default everything from entity table is selected
     * + name, status
     *
     * @param bool $sorted
     * @param array $optionalAttributes
     * @return Zend_Db_Select
     * @author Ultimate Module Creator
     */
    protected function _createCollectionDataSelect($sorted = true, $optionalAttributes = array())
    {
        $select = $this->_getDefaultCollection($sorted ? $this->_orderField : false)
            ->getSelect();
        // add attributes to select
        $attributes = array('name', 'status');
        if ($optionalAttributes) {
            $attributes = array_unique(array_merge($attributes, $optionalAttributes));
        }
        foreach ($attributes as $attributeCode) {
            /* @var $attribute Mage_Eav_Model_Entity_Attribute */
            $attribute = Mage::getResourceSingleton('amazon_products_amazon products/amazonproduct')->getAttribute($attributeCode);
            // join non-static attribute table
            if (!$attribute->getBackend()->isStatic()) {
                $tableDefault   = sprintf('d_%s', $attributeCode);
                $tableStore     = sprintf('s_%s', $attributeCode);
                $valueExpr      = $this->_conn
                    ->getCheckSql("{$tableStore}.value_id > 0", "{$tableStore}.value", "{$tableDefault}.value");

                $select
                    ->joinLeft(
                        array($tableDefault => $attribute->getBackend()->getTable()),
                        sprintf(
                            '%1$s.entity_id=e.entity_id AND %1$s.attribute_id=%2$d'.
                            ' AND %1$s.entity_type_id=e.entity_type_id AND %1$s.store_id=%3$d',
                            $tableDefault,
                            $attribute->getId(),
                            Mage_Core_Model_App::ADMIN_STORE_ID
                        ),
                        array($attributeCode => 'value')
                    )
                    ->joinLeft(
                        array($tableStore => $attribute->getBackend()->getTable()),
                        sprintf(
                            '%1$s.entity_id=e.entity_id AND %1$s.attribute_id=%2$d'.
                            ' AND %1$s.entity_type_id=e.entity_type_id AND %1$s.store_id=%3$d',
                            $tableStore,
                            $attribute->getId(),
                            $this->getStoreId()
                        ),
                        array($attributeCode => $valueExpr)
                    );
            }
        }
        return $select;
    }

    /**
     * Set store Id
     *
     * @access public
     * @param integer $storeId
     * @return amazon_products_Amazon Products_Model_Resource_Amazonproduct_Tree
     * @author Ultimate Module Creator
     */
    public function setStoreId($storeId)
    {
        $this->_storeId = $storeId;
        return $this;
    }

    /**
     * Return store id
     *
     * @access public
     * @return integer
     * @author Ultimate Module Creator
     */
    public function getStoreId()
    {
        if ($this->_storeId === null) {
            return Mage::app()->getStore()->getId();
        }
        return $this->_storeId;
    }

    /**
     * Get real existing amazon product ids by specified ids
     *
     * @access public
     * @param array $ids
     * @return array
     * @author Ultimate Module Creator
     */
    public function getExistingAmazonproductIdsBySpecifiedIds($ids)
    {
        if (empty($ids)) {
            return array();
        }
        if (!is_array($ids)) {
            $ids = array($ids);
        }
        $select = $this->_conn->select()
            ->from($this->_table, array('entity_id'))
            ->where('entity_id IN (?)', $ids);
        return $this->_conn->fetchCol($select);
    }

    /**
     * add collection data
     *
     * @access public
     * @param amazon_products_Amazon Products_Model_Resource_Amazonproduct_Collection $collection
     * @param boolean $sorted
     * @param array $exclude
     * @param boolean $toLoad
     * @param boolean $onlyActive
     * @return amazon_products_Amazon Products_Model_Resource_Amazonproduct_Tree
     * @author Ultimate Module Creator
     */
    public function addCollectionData(
        $collection = null,
        $sorted = false,
        $exclude = array(),
        $toLoad = true,
        $onlyActive = false
    )
    {
        if (is_null($collection)) {
            $collection = $this->getCollection($sorted);
        } else {
            $this->setCollection($collection);
        }
        if (!is_array($exclude)) {
            $exclude = array($exclude);
        }
        $nodeIds = array();
        foreach ($this->getNodes() as $node) {
            if (!in_array($node->getId(), $exclude)) {
                $nodeIds[] = $node->getId();
            }
        }
        $collection->addIdFilter($nodeIds);
        if ($onlyActive) {
            $disabledIds = $this->_getDisabledIds($collection);
            if ($disabledIds) {
                $collection->addAttributeToFilter('entity_id', array('nin' => $disabledIds));
            }
            $collection->addAttributeToFilter('status', 1);
        }
        if ($toLoad) {
            $collection->load();
            foreach ($collection as $amazonproduct) {
                if ($this->getNodeById($amazonproduct->getId())) {
                    $this->getNodeById($amazonproduct->getId())->addData($amazonproduct->getData());
                }
            }
            foreach ($this->getNodes() as $node) {
                if (!$collection->getItemById($node->getId()) && $node->getParent()) {
                    $this->removeNode($node);
                }
            }
        }
        return $this;
    }

    /**
     * Add inactive amazon products ids
     *
     * @access public
     * @param unknown_type $ids
     * @return amazon_products_Amazon Products_Model_Resource_Amazonproduct_Tree
     * @author Ultimate Module Creator
     */
    public function addInactiveAmazonproductIds($ids)
    {
        if (!is_array($this->_inactiveAmazonproductIds)) {
            $this->_initInactiveAmazonproductIds();
        }
        $this->_inactiveAmazonproductIds = array_merge($ids, $this->_inactiveAmazonproductIds);
        return $this;
    }

    /**
     * Retrieve inactive amazon products ids
     *
     * @access protected
     * @return amazon_products_Amazon Products_Model_Resource_Amazonproduct_Tree
     * @author Ultimate Module Creator
     */
    protected function _initInactiveAmazonproductIds()
    {
        $this->_inactiveAmazonproductIds = array();
        return $this;
    }
    /**
     * Retrieve inactive amazon products ids
     *
     * @access public
     * @return array
     * @author Ultimate Module Creator
     */
    public function getInactiveAmazonproductIds()
    {
        if (!is_array($this->_inactiveAmazonproductIds)) {
            $this->_initInactiveAmazonproductIds();
        }
        return $this->_inactiveAmazonproductIds;
    }

    /**
     * Return disable amazon product ids
     *
     * @access protected
     * @param amazon_products_Amazon Products_Model_Resource_Amazonproduct_Collection $collection
     * @return array
     * @author Ultimate Module Creator
     */
    protected function _getDisabledIds($collection)
    {
        $this->_inactiveItems = $this->getInactiveAmazonproductIds();
        $this->_inactiveItems = array_merge(
            $this->_getInactiveItemIds($collection),
            $this->_inactiveItems
        );
        $allIds = $collection->getAllIds();
        $disabledIds = array();

        foreach ($allIds as $id) {
            $parents = $this->getNodeById($id)->getPath();
            foreach ($parents as $parent) {
                if (!$this->_getItemIsActive($parent->getId())) {
                    $disabledIds[] = $id;
                    continue;
                }
            }
        }
        return $disabledIds;
    }

    /**
     * Retrieve inactive amazon product item ids
     *
     * @access protecte
     * @param amazon_products_Amazon Products_Model_Resource_Amazonproduct_Collection $collection
     * @return array
     * @author Ultimate Module Creator
     */
    protected function _getInactiveItemIds($collection)
    {
        $filter = $collection->getAllIdsSql();
        $table = Mage::getSingleton('core/resource')->getTable('amazon_products_amazon products/amazonproduct');
        $bind = array(
            'cond' => 0,
        );
        $select = $this->_conn->select()
            ->from(array('d'=>$table), array('d.entity_id'))
            ->where('d.entity_id IN (?)', new Zend_Db_Expr($filter))
            ->where('status = :cond');
        return $this->_conn->fetchCol($select, $bind);
    }

    /**
     * Check is amazon product items active
     *
     * @access protecte
     * @param int $id
     * @return boolean
     * @author Ultimate Module Creator
     */
    protected function _getItemIsActive($id)
    {
        if (!in_array($id, $this->_inactiveItems)) {
            return true;
        }
        return false;
    }
}
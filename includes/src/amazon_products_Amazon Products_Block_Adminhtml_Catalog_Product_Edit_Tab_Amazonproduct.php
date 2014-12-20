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
 * Amazon Product tab on product edit form
 *
 * @category    amazon_products
 * @package     amazon_products_Amazon Products
 * @author      Ultimate Module Creator
 */
class amazon_products_Amazon Products_Block_Adminhtml_Catalog_Product_Edit_Tab_Amazonproduct extends amazon_products_Amazon Products_Block_Adminhtml_Amazonproduct_Tree
{
    protected $_amazonproductIds = null;
    protected $_selectedNodes = null;

    /**
     * constructor
     * Specify template to use
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('amazon_products_amazon products/catalog/product/edit/tab/amazonproduct.phtml');
    }

    /**
     * Retrieve currently edited product
     *
     * @access public
     * @return Mage_Catalog_Model_Product
     * @author Ultimate Module Creator
     */
    public function getProduct()
    {
        return Mage::registry('current_product');
    }

    /**
     * Return array with amazon product IDs which the product is assigned to
     *
     * @access public
     * @return array
     * @author Ultimate Module Creator
     */
    public function getAmazonproductIds()
    {
        if (is_null($this->_amazonproductIds)) {
            $selectedAmazonproducts = Mage::helper('amazon_products_amazon products/product')->getSelectedAmazonproducts($this->getProduct());
            $ids = array();
            foreach ($selectedAmazonproducts as $amazonproduct) {
                $ids[] = $amazonproduct->getId();
            }
            $this->_amazonproductIds = $ids;
        }
        return $this->_amazonproductIds;
    }

    /**
     * Forms string out of getAmazonproductIds()
     *
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getIdsString()
    {
        return implode(',', $this->getAmazonproductIds());
    }

    /**
     * Returns root node and sets 'checked' flag (if necessary)
     *
     * @access public
     * @return Varien_Data_Tree_Node
     * @author Ultimate Module Creator
     */
    public function getRootNode()
    {
        $root = $this->getRoot();
        if ($root && in_array($root->getId(), $this->getAmazonproductIds())) {
            $root->setChecked(true);
        }
        return $root;
    }

    /**
     * Returns root node
     *
     * @param amazon_products_Amazon Products_Model_Amazonproduct|null $parentNodeAmazonproduct
     * @param int  $recursionLevel
     * @return Varien_Data_Tree_Node
     * @author Ultimate Module Creator
     */
    public function getRoot($parentNodeAmazonproduct = null, $recursionLevel = 3)
    {
        if (!is_null($parentNodeAmazonproduct) && $parentNodeAmazonproduct->getId()) {
            return $this->getNode($parentNodeAmazonproduct, $recursionLevel);
        }
        $root = Mage::registry('amazonproduct_root');
        if (is_null($root)) {
            $rootId = Mage::helper('amazon_products_amazon products/amazonproduct')->getRootAmazonproductId();

            $ids = $this->getSelectedAmazonproductPathIds($rootId);
            $tree = Mage::getResourceSingleton('amazon_products_amazon products/amazonproduct_tree')
                ->loadByIds($ids, false, false);
            if ($this->getAmazonproduct()) {
                $tree->loadEnsuredNodes($this->getAmazonproduct(), $tree->getNodeById($rootId));
            }
            $tree->addCollectionData($this->getAmazonproductCollection());
            $root = $tree->getNodeById($rootId);
            Mage::register('amazonproduct_root', $root);
        }
        return $root;
    }

    /**
     * Returns array with configuration of current node
     *
     * @access protected
     * @param Varien_Data_Tree_Node $node
     * @param int $level How deep is the node in the tree
     * @return array
     * @author Ultimate Module Creator
     */
    protected function _getNodeJson($node, $level = 1)
    {
        $item = parent::_getNodeJson($node, $level);
        if ($this->_isParentSelectedAmazonproduct($node)) {
            $item['expanded'] = true;
        }
        if (in_array($node->getId(), $this->getAmazonproductIds())) {
            $item['checked'] = true;
        }
        return $item;
    }

    /**
     * Returns whether $node is a parent (not exactly direct) of a selected node
     *
     * @access protected
     * @param Varien_Data_Tree_Node $node
     * @return bool
     * @author Ultimate Module Creator
     */
    protected function _isParentSelectedAmazonproduct($node)
    {
        $result = false;
        // Contains string with all amazon product IDs of children (not exactly direct) of the node
        $allChildren = $node->getAllChildren();
        if ($allChildren) {
            $selectedAmazonproductIds = $this->getAmazonproductIds();
            $allChildrenArr = explode(',', $allChildren);
            for ($i = 0, $cnt = count($selectedAmazonproductIds); $i < $cnt; $i++) {
                $isSelf = $node->getId() == $selectedAmazonproductIds[$i];
                if (!$isSelf && in_array($selectedAmazonproductIds[$i], $allChildrenArr)) {
                    $result = true;
                    break;
                }
            }
        }
        return $result;
    }

    /**
     * Returns array with nodes those are selected (contain current product)
     *
     * @access protected
     * @return array
     * @author Ultimate Module Creator
     */
    protected function _getSelectedNodes()
    {
        if ($this->_selectedNodes === null) {
            $this->_selectedNodes = array();
            $root = $this->getRoot();
            foreach ($this->getAmazonproductIds() as $amazonproductId) {
                if ($root) {
                    $this->_selectedNodes[] = $root->getTree()->getNodeById($amazonproductId);
                }
            }
        }
        return $this->_selectedNodes;
    }

    /**
     * Returns JSON-encoded array of amazon product children
     *
     * @access public
     * @param int $amazonproductId
     * @return string
     * @author Ultimate Module Creator
     */
    public function getAmazonproductChildrenJson($amazonproductId)
    {
        $amazonproduct = Mage::getModel('amazon_products_amazon products/amazonproduct')->load($amazonproductId);
        $node = $this->getRoot($amazonproduct, 1)->getTree()->getNodeById($amazonproductId);
        if (!$node || !$node->hasChildren()) {
            return '[]';
        }

        $children = array();
        foreach ($node->getChildren() as $child) {
            $children[] = $this->_getNodeJson($child);
        }
        return Mage::helper('core')->jsonEncode($children);
    }

    /**
     * Returns URL for loading tree
     *
     * @access public
     * @param null $expanded
     * @return string
     * @author Ultimate Module Creator
     */
    public function getLoadTreeUrl($expanded = null)
    {
        return $this->getUrl('*/*/amazonproductsJson', array('_current' => true));
    }

    /**
     * Return distinct path ids of selected amazon products
     *
     * @access public
     * @param mixed $rootId Root amazon product Id for context
     * @return array
     * @author Ultimate Module Creator
     */
    public function getSelectedAmazonproductPathIds($rootId = false)
    {
        $ids = array();
        $amazonproductIds = $this->getAmazonproductIds();
        if (empty($amazonproductIds)) {
            return array();
        }
        $collection = Mage::getResourceModel('amazon_products_amazon products/amazonproduct_collection');

        if ($rootId) {
            $collection->addFieldToFilter('parent_id', $rootId);
        } else {
            $collection->addFieldToFilter('entity_id', array('in'=>$amazonproductIds));
        }

        foreach ($collection as $item) {
            if ($rootId && !in_array($rootId, $item->getPathIds())) {
                continue;
            }
            foreach ($item->getPathIds() as $id) {
                if (!in_array($id, $ids)) {
                    $ids[] = $id;
                }
            }
        }
        return $ids;
    }
}

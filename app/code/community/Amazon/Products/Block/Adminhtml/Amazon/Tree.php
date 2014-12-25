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
 * Amazon Product admin tree block
 *
 * @category    Amazon
 * @package     Amazon_Products
 * @author      Ultimate Module Creator
 */
class Amazon_Products_Block_Adminhtml_Amazon_Tree extends Amazon_Products_Block_Adminhtml_Amazon_Abstract
{
    /**
     * constructor
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('amazon_products/amazon/tree.phtml');
        $this->setUseAjax(true);
        $this->_withProductCount = true;
    }

    /**
     * prepare the layout
     *
     * @access protected
     * @return Amazon_Products_Block_Adminhtml_Amazon_Tree
     * @author Ultimate Module Creator
     */
    protected function _prepareLayout()
    {
        $addUrl = $this->getUrl(
            "*/*/add",
            array(
                '_current'=>true,
                'id'=>null,
                '_query' => false
            )
        );

        $this->setChild(
            'add_sub_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(
                    array(
                        'label'   => Mage::helper('amazon_products')->__('Add Child Amazon Product'),
                        'onclick' => "addNew('".$addUrl."', false)",
                        'class'   => 'add',
                        'id'      => 'add_child_amazon_button',
                        'style'   => $this->canAddChild() ? '' : 'display: none;'
                    )
                )
        );

        $this->setChild(
            'add_root_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(
                    array(
                        'label'   => Mage::helper('amazon_products')->__('Add Root Amazon Product'),
                        'onclick' => "addNew('".$addUrl."', true)",
                        'class'   => 'add',
                        'id'      => 'add_root_amazon_button'
                    )
                )
        );
        return parent::_prepareLayout();
    }

    /**
     * get the amazon product collection
     *
     * @access public
     * @return Amazon_Products_Model_Resource_Amazon_Collection
     * @author Ultimate Module Creator
     */
    public function getAmazonCollection()
    {
        $collection = $this->getData('amazon_collection');
        if (is_null($collection)) {
            $collection = Mage::getModel('amazon_products/amazon')->getCollection();
            $this->setData('amazon_collection', $collection);
        }
        return $collection;
    }

    /**
     * get html for add root button
     *
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getAddRootButtonHtml()
    {
        return $this->getChildHtml('add_root_button');
    }

    /**
     * get html for add child button
     *
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getAddSubButtonHtml()
    {
        return $this->getChildHtml('add_sub_button');
    }

    /**
     * get html for expand button
     *
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getExpandButtonHtml()
    {
        return $this->getChildHtml('expand_button');
    }

    /**
     * get html for add collapse button
     *
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getCollapseButtonHtml()
    {
        return $this->getChildHtml('collapse_button');
    }

    /**
     * get url for tree load
     *
     * @access public
     * @param mxed $expanded
     * @return string
     * @author Ultimate Module Creator
     */
    public function getLoadTreeUrl($expanded=null)
    {
        $params = array('_current' => true, 'id' => null, 'store' => null);
        if ((is_null($expanded) &&
            Mage::getSingleton('admin/session')->getAmazonIsTreeWasExpanded()) ||
            $expanded == true) {
            $params['expand_all'] = true;
        }
        return $this->getUrl('*/*/amazonsJson', $params);
    }

    /**
     * get url for loading nodes
     *
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getNodesUrl()
    {
        return $this->getUrl('*/products_amazons/jsonTree');
    }

    /**
     * check if tree is expanded
     *
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getIsWasExpanded()
    {
        return Mage::getSingleton('admin/session')->getAmazonIsTreeWasExpanded();
    }

    /**
     * get url for moving amazon product
     *
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getMoveUrl()
    {
        return $this->getUrl('*/products_amazon/move');
    }

    /**
     * get the tree as json
     *
     * @access public
     * @param mixed $parentNodeAmazon
     * @return string
     * @author Ultimate Module Creator
     */
    public function getTree($parentNodeAmazon = null)
    {
        $rootArray = $this->_getNodeJson($this->getRoot($parentNodeAmazon));
        $tree = isset($rootArray['children']) ? $rootArray['children'] : array();
        return $tree;
    }

    /**
     * get the tree as json
     *
     * @access public
     * @param mixed $parentNodeAmazon
     * @return string
     * @author Ultimate Module Creator
     */
    public function getTreeJson($parentNodeAmazon = null)
    {
        $rootArray = $this->_getNodeJson($this->getRoot($parentNodeAmazon));
        $json = Mage::helper('core')->jsonEncode(isset($rootArray['children']) ? $rootArray['children'] : array());
        return $json;
    }

    /**
     * Get JSON of array of amazon products, that are breadcrumbs for specified amazon product path
     *
     * @access public
     * @param string $path
     * @param string $javascriptVarName
     * @return string
     * @author Ultimate Module Creator
     */
    public function getBreadcrumbsJavascript($path, $javascriptVarName)
    {
        if (empty($path)) {
            return '';
        }

        $amazons = Mage::getResourceSingleton('amazon_products/amazon_tree')
            ->loadBreadcrumbsArray($path);
        if (empty($amazons)) {
            return '';
        }
        foreach ($amazons as $key => $amazon) {
            $amazons[$key] = $this->_getNodeJson($amazon);
        }
        return
            '<script type="text/javascript">'
            . $javascriptVarName . ' = ' . Mage::helper('core')->jsonEncode($amazons) . ';'
            . ($this->canAddChild() ? '$("add_child_amazon_button").show();' : '$("add_child_amazon_button").hide();')
            . '</script>';
    }

    /**
     * Get JSON of a tree node or an associative array
     *
     * @access protected
     * @param Varien_Data_Tree_Node|array $node
     * @param int $level
     * @return string
     * @author Ultimate Module Creator
     */
    protected function _getNodeJson($node, $level = 0)
    {
        // create a node from data array
        if (is_array($node)) {
            $node = new Varien_Data_Tree_Node($node, 'entity_id', new Varien_Data_Tree);
        }
        $item = array();
        $item['text'] = $this->buildNodeName($node);
        $item['id']   = $node->getId();
        $item['path'] = $node->getData('path');
        $item['cls']  = 'folder';
        if ($node->getStatus()) {
            $item['cls'] .= ' active-category';
        } else {
            $item['cls'] .= ' no-active-category';
        }
        $item['allowDrop'] = true;
        $item['allowDrag'] = true;
        if ((int)$node->getChildrenCount()>0) {
            $item['children'] = array();
        }
        $isParent = $this->_isParentSelectedAmazon($node);
        if ($node->hasChildren()) {
            $item['children'] = array();
            if (!($this->getUseAjax() && $node->getLevel() > 1 && !$isParent)) {
                foreach ($node->getChildren() as $child) {
                    $item['children'][] = $this->_getNodeJson($child, $level+1);
                }
            }
        }
        if ($isParent || $node->getLevel() < 1) {
            $item['expanded'] = true;
        }
        return $item;
    }

    /**
     * Get node label
     *
     * @access public
     * @param Varien_Object $node
     * @return string
     * @author Ultimate Module Creator
     */
    public function buildNodeName($node)
    {
        $result = $this->escapeHtml($node->getName());
        return $result;
    }

    /**
     * check if entity is movable
     *
     * @access protected
     * @param Varien_Object $node
     * @return bool
     * @author Ultimate Module Creator
     */
    protected function _isAmazonMoveable($node)
    {
        return true;
    }

    /**
     * check if parent is selected
     *
     * @access protected
     * @param Varien_Object $node
     * @return bool
     * @author Ultimate Module Creator
     */
    protected function _isParentSelectedAmazon($node)
    {
        if ($node && $this->getAmazon()) {
            $pathIds = $this->getAmazon()->getPathIds();
            if (in_array($node->getId(), $pathIds)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Check if page loaded by outside link to amazon product edit
     *
     * @access public
     * @return boolean
     * @author Ultimate Module Creator
     */
    public function isClearEdit()
    {
        return (bool) $this->getRequest()->getParam('clear');
    }

    /**
     * Check availability of adding root amazon product
     *
     * @access public
     * @return boolean
     * @author Ultimate Module Creator
     */
    public function canAddRootAmazon()
    {
        return true;
    }

    /**
     * Check availability of adding child amazon product
     *
     * @access public
     * @return boolean
     */
    public function canAddChild()
    {
        return true;
    }
}

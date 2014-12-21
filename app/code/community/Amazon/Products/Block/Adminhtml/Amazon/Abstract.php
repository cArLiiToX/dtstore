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
 * Amazon Product admin block abstract
 *
 * @category    Amazon
 * @package     Amazon_Products
 * @author      Ultimate Module Creator
 */
class Amazon_Products_Block_Adminhtml_Amazon_Abstract extends Mage_Adminhtml_Block_Template
{
    /**
     * get current amazon product
     *
     * @access public
     * @return Amazon_Products_Model_Entity
     * @author Ultimate Module Creator
     */
    public function getAmazon()
    {
        return Mage::registry('amazon');
    }

    /**
     * get current amazon product id
     *
     * @access public
     * @return int
     * @author Ultimate Module Creator
     */
    public function getAmazonId()
    {
        if ($this->getAmazon()) {
            return $this->getAmazon()->getId();
        }
        return null;
    }

    /**
     * get current amazon product Name
     *
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getAmazonName()
    {
        return $this->getAmazon()->getName();
    }

    /**
     * get current amazon product path
     *
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getAmazonPath()
    {
        if ($this->getAmazon()) {
            return $this->getAmazon()->getPath();
        }
        return Mage::helper('amazon_products/amazon')->getRootAmazonId();
    }

    /**
     * check if there is a root amazon product
     *
     * @access public
     * @return bool
     * @author Ultimate Module Creator
     */
    public function hasRootAmazon()
    {
        $root = $this->getRoot();
        if ($root && $root->getId()) {
            return true;
        }
        return false;
    }

    /**
     * get the root
     *
     * @access public
     * @param Amazon_Products_Model_Amazon|null $parentNodeAmazon
     * @param int $recursionLevel
     * @return Varien_Data_Tree_Node
     * @author Ultimate Module Creator
     */
    public function getRoot($parentNodeAmazon = null, $recursionLevel = 3)
    {
        if (!is_null($parentNodeAmazon) && $parentNodeAmazon->getId()) {
            return $this->getNode($parentNodeAmazon, $recursionLevel);
        }
        $root = Mage::registry('root');
        if (is_null($root)) {
            $rootId = Mage::helper('amazon_products/amazon')->getRootAmazonId();
            $tree = Mage::getResourceSingleton('amazon_products/amazon_tree')
                ->load(null, $recursionLevel);
            if ($this->getAmazon()) {
                $tree->loadEnsuredNodes($this->getAmazon(), $tree->getNodeById($rootId));
            }
            $tree->addCollectionData($this->getAmazonCollection());
            $root = $tree->getNodeById($rootId);
            if ($root && $rootId != Mage::helper('amazon_products/amazon')->getRootAmazonId()) {
                $root->setIsVisible(true);
            } elseif ($root && $root->getId() == Mage::helper('amazon_products/amazon')->getRootAmazonId()) {
                $root->setName(Mage::helper('amazon_products')->__('Root'));
            }
            Mage::register('root', $root);
        }
        return $root;
    }

    /**
     * Get and register amazon products root by specified amazon products IDs
     *
     * @accsess public
     * @param array $ids
     * @return Varien_Data_Tree_Node
     * @author Ultimate Module Creator
     */
    public function getRootByIds($ids)
    {
        $root = Mage::registry('root');
        if (null === $root) {
            $amazonTreeResource = Mage::getResourceSingleton('amazon_products/amazon_tree');
            $ids     = $amazonTreeResource->getExistingAmazonIdsBySpecifiedIds($ids);
            $tree   = $amazonTreeResource->loadByIds($ids);
            $rootId = Mage::helper('amazon_products/amazon')->getRootAmazonId();
            $root   = $tree->getNodeById($rootId);
            if ($root && $rootId != Mage::helper('amazon_products/amazon')->getRootAmazonId()) {
                $root->setIsVisible(true);
            } elseif ($root && $root->getId() == Mage::helper('amazon_products/amazon')->getRootAmazonId()) {
                $root->setName(Mage::helper('amazon_products')->__('Root'));
            }
            $tree->addCollectionData($this->getAmazonCollection());
            Mage::register('root', $root);
        }
        return $root;
    }

    /**
     * get specific node
     *
     * @access public
     * @param Amazon_Products_Model_Amazon $parentNodeAmazon
     * @param $int $recursionLevel
     * @return Varien_Data_Tree_Node
     * @author Ultimate Module Creator
     */
    public function getNode($parentNodeAmazon, $recursionLevel = 2)
    {
        $tree = Mage::getResourceModel('amazon_products/amazon_tree');
        $nodeId     = $parentNodeAmazon->getId();
        $parentId   = $parentNodeAmazon->getParentId();
        $node = $tree->loadNode($nodeId);
        $node->loadChildren($recursionLevel);
        if ($node && $nodeId != Mage::helper('amazon_products/amazon')->getRootAmazonId()) {
            $node->setIsVisible(true);
        } elseif ($node && $node->getId() == Mage::helper('amazon_products/amazon')->getRootAmazonId()) {
            $node->setName(Mage::helper('amazon_products')->__('Root'));
        }
        $tree->addCollectionData($this->getAmazonCollection());
        return $node;
    }

    /**
     * get url for saving data
     *
     * @access public
     * @param array $args
     * @return string
     * @author Ultimate Module Creator
     */
    public function getSaveUrl(array $args = array())
    {
        $params = array('_current'=>true);
        $params = array_merge($params, $args);
        return $this->getUrl('*/*/save', $params);
    }

    /**
     * get url for edit
     *
     * @access public
     * @param array $args
     * @return string
     * @author Ultimate Module Creator
     */
    public function getEditUrl()
    {
        return $this->getUrl(
            "*/products_amazon/edit",
            array('_current' => true, '_query'=>false, 'id' => null, 'parent' => null)
        );
    }

    /**
     * Return root ids
     *
     * @access public
     * @return array
     * @author Ultimate Module Creator
     */
    public function getRootIds()
    {
        return array(Mage::helper('amazon_products/amazon')->getRootAmazonId());
    }
}

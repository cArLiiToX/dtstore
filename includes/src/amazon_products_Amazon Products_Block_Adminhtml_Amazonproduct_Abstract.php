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
 * Amazon Product admin block abstract
 *
 * @category    amazon_products
 * @package     amazon_products_Amazon Products
 * @author      Ultimate Module Creator
 */
class amazon_products_Amazon Products_Block_Adminhtml_Amazonproduct_Abstract extends Mage_Adminhtml_Block_Template
{
    /**
     * get current amazon product
     *
     * @access public
     * @return amazon_products_Amazon Products_Model_Entity
     * @author Ultimate Module Creator
     */
    public function getAmazonproduct()
    {
        return Mage::registry('amazonproduct');
    }

    /**
     * get current amazon product id
     *
     * @access public
     * @return int
     * @author Ultimate Module Creator
     */
    public function getAmazonproductId()
    {
        if ($this->getAmazonproduct()) {
            return $this->getAmazonproduct()->getId();
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
    public function getAmazonproductName()
    {
        return $this->getAmazonproduct()->getName();
    }

    /**
     * get current amazon product path
     *
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getAmazonproductPath()
    {
        if ($this->getAmazonproduct()) {
            return $this->getAmazonproduct()->getPath();
        }
        return Mage::helper('amazon_products_amazon products/amazonproduct')->getRootAmazonproductId();
    }

    /**
     * check if there is a root amazon product
     *
     * @access public
     * @return bool
     * @author Ultimate Module Creator
     */
    public function hasRootAmazonproduct()
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
     * @param amazon_products_Amazon Products_Model_Amazonproduct|null $parentNodeAmazonproduct
     * @param int $recursionLevel
     * @return Varien_Data_Tree_Node
     * @author Ultimate Module Creator
     */
    public function getRoot($parentNodeAmazonproduct = null, $recursionLevel = 3)
    {
        if (!is_null($parentNodeAmazonproduct) && $parentNodeAmazonproduct->getId()) {
            return $this->getNode($parentNodeAmazonproduct, $recursionLevel);
        }
        $root = Mage::registry('root');
        if (is_null($root)) {
            $rootId = Mage::helper('amazon_products_amazon products/amazonproduct')->getRootAmazonproductId();
            $tree = Mage::getResourceSingleton('amazon_products_amazon products/amazonproduct_tree')
                ->load(null, $recursionLevel);
            if ($this->getAmazonproduct()) {
                $tree->loadEnsuredNodes($this->getAmazonproduct(), $tree->getNodeById($rootId));
            }
            $tree->addCollectionData($this->getAmazonproductCollection());
            $root = $tree->getNodeById($rootId);
            if ($root && $rootId != Mage::helper('amazon_products_amazon products/amazonproduct')->getRootAmazonproductId()) {
                $root->setIsVisible(true);
            } elseif ($root && $root->getId() == Mage::helper('amazon_products_amazon products/amazonproduct')->getRootAmazonproductId()) {
                $root->setName(Mage::helper('amazon_products_amazon products')->__('Root'));
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
            $amazonproductTreeResource = Mage::getResourceSingleton('amazon_products_amazon products/amazonproduct_tree');
            $ids     = $amazonproductTreeResource->getExistingAmazonproductIdsBySpecifiedIds($ids);
            $tree   = $amazonproductTreeResource->loadByIds($ids);
            $rootId = Mage::helper('amazon_products_amazon products/amazonproduct')->getRootAmazonproductId();
            $root   = $tree->getNodeById($rootId);
            if ($root && $rootId != Mage::helper('amazon_products_amazon products/amazonproduct')->getRootAmazonproductId()) {
                $root->setIsVisible(true);
            } elseif ($root && $root->getId() == Mage::helper('amazon_products_amazon products/amazonproduct')->getRootAmazonproductId()) {
                $root->setName(Mage::helper('amazon_products_amazon products')->__('Root'));
            }
            $tree->addCollectionData($this->getAmazonproductCollection());
            Mage::register('root', $root);
        }
        return $root;
    }

    /**
     * get specific node
     *
     * @access public
     * @param amazon_products_Amazon Products_Model_Amazonproduct $parentNodeAmazonproduct
     * @param $int $recursionLevel
     * @return Varien_Data_Tree_Node
     * @author Ultimate Module Creator
     */
    public function getNode($parentNodeAmazonproduct, $recursionLevel = 2)
    {
        $tree = Mage::getResourceModel('amazon_products_amazon products/amazonproduct_tree');
        $nodeId     = $parentNodeAmazonproduct->getId();
        $parentId   = $parentNodeAmazonproduct->getParentId();
        $node = $tree->loadNode($nodeId);
        $node->loadChildren($recursionLevel);
        if ($node && $nodeId != Mage::helper('amazon_products_amazon products/amazonproduct')->getRootAmazonproductId()) {
            $node->setIsVisible(true);
        } elseif ($node && $node->getId() == Mage::helper('amazon_products_amazon products/amazonproduct')->getRootAmazonproductId()) {
            $node->setName(Mage::helper('amazon_products_amazon products')->__('Root'));
        }
        $tree->addCollectionData($this->getAmazonproductCollection());
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
            "*/amazon products_amazonproduct/edit",
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
        return array(Mage::helper('amazon_products_amazon products/amazonproduct')->getRootAmazonproductId());
    }
}

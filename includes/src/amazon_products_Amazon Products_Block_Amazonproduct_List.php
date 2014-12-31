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
 * Amazon Product list block
 *
 * @category    amazon_products
 * @package     amazon_products_Amazon Products
 * @author Ultimate Module Creator
 */
class amazon_products_Amazon Products_Block_Amazonproduct_List extends Mage_Core_Block_Template
{
    /**
     * initialize
     *
     * @access public
     * @author Ultimate Module Creator
     */
    public function __construct()
    {
        parent::__construct();
        $amazonproducts = Mage::getResourceModel('amazon_products_amazon products/amazonproduct_collection')
                         ->setStoreId(Mage::app()->getStore()->getId())
                         ->addAttributeToSelect('*')
                         ->addAttributeToFilter('status', 1);
        ;
        $amazonproducts->getSelect()->order('e.position');
        $this->setAmazonproducts($amazonproducts);
    }

    /**
     * prepare the layout
     *
     * @access protected
     * @return amazon_products_Amazon Products_Block_Amazonproduct_List
     * @author Ultimate Module Creator
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $this->getAmazonproducts()->addFieldToFilter('level', 1);
        if ($this->_getDisplayMode() == 0) {
            $pager = $this->getLayout()->createBlock(
                'page/html_pager',
                'amazon_products_amazon products.amazonproducts.html.pager'
            )
            ->setCollection($this->getAmazonproducts());
            $this->setChild('pager', $pager);
            $this->getAmazonproducts()->load();
        }
        return $this;
    }

    /**
     * get the pager html
     *
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }

    /**
     * get the display mode
     *
     * @access protected
     * @return int
     * @author Ultimate Module Creator
     */
    protected function _getDisplayMode()
    {
        return Mage::getStoreConfigFlag('amazon_products_amazon products/amazonproduct/tree');
    }

    /**
     * draw amazon product
     *
     * @access public
     * @param amazon_products_Amazon Products_Model_Amazonproduct
     * @param int $level
     * @return int
     * @author Ultimate Module Creator
     */
    public function drawAmazonproduct($amazonproduct, $level = 0)
    {
        $html = '';
        $recursion = $this->getRecursion();
        if ($recursion !== '0' && $level >= $recursion) {
            return '';
        }
        if (!$amazonproduct->getStatus()) {
            return '';
        }
        $amazonproduct->setStoreId(Mage::app()->getStore()->getId());
        $children = $amazonproduct->getChildrenAmazonproducts()->addAttributeToSelect('*');
        $activeChildren = array();
        if ($recursion == 0 || $level < $recursion-1) {
            foreach ($children as $child) {
                if ($child->getStatus()) {
                    $activeChildren[] = $child;
                }
            }
        }
        $html .= '<li>';
        $html .= '<a href="#">'.$amazonproduct->getName().'</a>';
        if (count($activeChildren) > 0) {
            $html .= '<ul>';
            foreach ($children as $child) {
                $html .= $this->drawAmazonproduct($child, $level+1);
            }
            $html .= '</ul>';
        }
        $html .= '</li>';
        return $html;
    }

    /**
     * get recursion
     *
     * @access public
     * @return int
     * @author Ultimate Module Creator
     */
    public function getRecursion()
    {
        if (!$this->hasData('recursion')) {
            $this->setData('recursion', Mage::getStoreConfig('amazon_products_amazon products/amazonproduct/recursion'));
        }
        return $this->getData('recursion');
    }
}

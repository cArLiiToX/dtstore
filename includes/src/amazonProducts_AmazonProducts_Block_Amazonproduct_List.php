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
 * Amazon Product list block
 *
 * @category    amazonProducts
 * @package     amazonProducts_AmazonProducts
 * @author Ultimate Module Creator
 */
class amazonProducts_AmazonProducts_Block_Amazonproduct_List extends Mage_Core_Block_Template
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
        $amazonproducts = Mage::getResourceModel('amazonproducts_amazonproducts/amazonproduct_collection')
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
     * @return amazonProducts_AmazonProducts_Block_Amazonproduct_List
     * @author Ultimate Module Creator
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $this->getAmazonproducts()->addFieldToFilter('level', 1);
        if ($this->_getDisplayMode() == 0) {
            $pager = $this->getLayout()->createBlock(
                'page/html_pager',
                'amazonproducts_amazonproducts.amazonproducts.html.pager'
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
        return Mage::getStoreConfigFlag('amazonproducts_amazonproducts/amazonproduct/tree');
    }

    /**
     * draw amazon product
     *
     * @access public
     * @param amazonProducts_AmazonProducts_Model_Amazonproduct
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
            $this->setData('recursion', Mage::getStoreConfig('amazonproducts_amazonproducts/amazonproduct/recursion'));
        }
        return $this->getData('recursion');
    }
}

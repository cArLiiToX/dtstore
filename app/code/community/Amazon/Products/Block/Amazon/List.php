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
 * Amazon Product list block
 *
 * @category    Amazon
 * @package     Amazon_Products
 * @author Ultimate Module Creator
 */
class Amazon_Products_Block_Amazon_List extends Mage_Core_Block_Template
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
        $amazons = Mage::getResourceModel('amazon_products/amazon_collection')
                         ->addFieldToFilter('status', 1);
        $amazons->setOrder('name', 'asc');
        $this->setAmazons($amazons);
    }

    /**
     * prepare the layout
     *
     * @access protected
     * @return Amazon_Products_Block_Amazon_List
     * @author Ultimate Module Creator
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $pager = $this->getLayout()->createBlock(
            'page/html_pager',
            'amazon_products.amazon.html.pager'
        )
        ->setCollection($this->getAmazons());
        $this->setChild('pager', $pager);
        $this->getAmazons()->load();
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
}

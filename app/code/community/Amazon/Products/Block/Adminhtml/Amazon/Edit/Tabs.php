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
 * Amazon Product admin edit tabs
 *
 * @category    Amazon
 * @package     Amazon_Products
 * @author      Ultimate Module Creator
 */
class Amazon_Products_Block_Adminhtml_Amazon_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    /**
     * Initialize Tabs
     *
     * @access public
     * @author Ultimate Module Creator
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('amazon_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('amazon_products')->__('Amazon Product'));
    }

    /**
     * before render html
     *
     * @access protected
     * @return Amazon_Products_Block_Adminhtml_Amazon_Edit_Tabs
     * @author Ultimate Module Creator
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'form_amazon',
            array(
                'label'   => Mage::helper('amazon_products')->__('Amazon Product'),
                'title'   => Mage::helper('amazon_products')->__('Amazon Product'),
                'content' => $this->getLayout()->createBlock(
                    'amazon_products/adminhtml_amazon_edit_tab_form'
                )
                ->toHtml(),
            )
        );
        $this->addTab(
            'products',
            array(
                'label' => Mage::helper('amazon_products')->__('Associated products'),
                'url'   => $this->getUrl('*/*/products', array('_current' => true)),
                'class' => 'ajax'
            )
        );
        return parent::_beforeToHtml();
    }

    /**
     * Retrieve amazon product entity
     *
     * @access public
     * @return Amazon_Products_Model_Amazon
     * @author Ultimate Module Creator
     */
    public function getAmazon()
    {
        return Mage::registry('current_amazon');
    }
}

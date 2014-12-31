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
 * Adminhtml amazon product attribute edit page tabs
 *
 * @category    amazon_products
 * @package     amazon_products_Amazon Products
 * @author      Ultimate Module Creator
 */
class amazon_products_Amazon Products_Block_Adminhtml_Amazonproduct_Attribute_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    /**
     * constructor
     *
     * @access public
     * @author Ultimate Module Creator
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('amazonproduct_attribute_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('amazon_products_amazon products')->__('Attribute Information'));
    }

    /**
     * add attribute tabs
     *
     * @access protected
     * @return amazon_products_Amazon Products_Adminhtml_Amazonproduct_Attribute_Edit_Tabs
     * @author Ultimate Module Creator
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'main',
            array(
                'label'     => Mage::helper('amazon_products_amazon products')->__('Properties'),
                'title'     => Mage::helper('amazon_products_amazon products')->__('Properties'),
                'content'   => $this->getLayout()->createBlock(
                    'amazon_products_amazon products/adminhtml_amazonproduct_attribute_edit_tab_main'
                )
                ->toHtml(),
                'active'    => true
            )
        );
        $this->addTab(
            'labels',
            array(
                'label'     => Mage::helper('amazon_products_amazon products')->__('Manage Label / Options'),
                'title'     => Mage::helper('amazon_products_amazon products')->__('Manage Label / Options'),
                'content'   => $this->getLayout()->createBlock(
                    'amazon_products_amazon products/adminhtml_amazonproduct_attribute_edit_tab_options'
                )
                ->toHtml(),
            )
        );
        return parent::_beforeToHtml();
    }
}

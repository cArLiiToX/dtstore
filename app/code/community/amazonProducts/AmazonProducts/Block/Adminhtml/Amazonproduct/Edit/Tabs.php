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
 * Amazon Product admin edit tabs
 *
 * @category    amazonProducts
 * @package     amazonProducts_AmazonProducts
 * @author      Ultimate Module Creator
 */
class amazonProducts_AmazonProducts_Block_Adminhtml_Amazonproduct_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    /**
     * Initialize Tabs
     *
     * @access public
     * @author Ultimate Module Creator
     */
    public function __construct()
    {
        $this->setId('amazonproduct_info_tabs');
        $this->setDestElementId('amazonproduct_tab_content');
        $this->setTitle(Mage::helper('amazonproducts_amazonproducts')->__('Amazon Product'));
        $this->setTemplate('widget/tabshoriz.phtml');
    }

    /**
     * Prepare Layout Content
     *
     * @access public
     * @return amazonProducts_AmazonProducts_Block_Adminhtml_Amazonproduct_Edit_Tabs
     */
    protected function _prepareLayout()
    {
        $this->addTab(
            'form_amazonproduct',
            array(
                'label'   => Mage::helper('amazonproducts_amazonproducts')->__('Amazon Product'),
                'title'   => Mage::helper('amazonproducts_amazonproducts')->__('Amazon Product'),
                'content' => $this->getLayout()->createBlock(
                    'amazonproducts_amazonproducts/adminhtml_amazonproduct_edit_tab_form'
                )
                ->toHtml(),
            )
        );
        if (!Mage::app()->isSingleStoreMode()) {
            $this->addTab(
                'form_store_amazonproduct',
                array(
                    'label'   => Mage::helper('amazonproducts_amazonproducts')->__('Store views'),
                    'title'   => Mage::helper('amazonproducts_amazonproducts')->__('Store views'),
                    'content' => $this->getLayout()->createBlock(
                        'amazonproducts_amazonproducts/adminhtml_amazonproduct_edit_tab_stores'
                    )
                    ->toHtml(),
                )
            );
        }
        $this->addTab(
            'products',
            array(
                'label'   => Mage::helper('amazonproducts_amazonproducts')->__('Associated Products'),
                'content' => $this->getLayout()->createBlock(
                    'amazonproducts_amazonproducts/adminhtml_amazonproduct_edit_tab_product',
                    'amazonproduct.product.grid'
                )
                ->toHtml(),
            )
        );
        return parent::_beforeToHtml();
    }

    /**
     * Retrieve amazon product entity
     *
     * @access public
     * @return amazonProducts_AmazonProducts_Model_Amazonproduct
     * @author Ultimate Module Creator
     */
    public function getAmazonproduct()
    {
        return Mage::registry('current_amazonproduct');
    }
}

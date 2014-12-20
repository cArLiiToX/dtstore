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
 * Adminhtml amazon product attribute edit page tabs
 *
 * @category    amazonProducts
 * @package     amazonProducts_AmazonProducts
 * @author      Ultimate Module Creator
 */
class amazonProducts_AmazonProducts_Block_Adminhtml_Amazonproduct_Attribute_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
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
        $this->setTitle(Mage::helper('amazonproducts_amazonproducts')->__('Attribute Information'));
    }

    /**
     * add attribute tabs
     *
     * @access protected
     * @return amazonProducts_AmazonProducts_Adminhtml_Amazonproduct_Attribute_Edit_Tabs
     * @author Ultimate Module Creator
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'main',
            array(
                'label'     => Mage::helper('amazonproducts_amazonproducts')->__('Properties'),
                'title'     => Mage::helper('amazonproducts_amazonproducts')->__('Properties'),
                'content'   => $this->getLayout()->createBlock(
                    'amazonproducts_amazonproducts/adminhtml_amazonproduct_attribute_edit_tab_main'
                )
                ->toHtml(),
                'active'    => true
            )
        );
        $this->addTab(
            'labels',
            array(
                'label'     => Mage::helper('amazonproducts_amazonproducts')->__('Manage Label / Options'),
                'title'     => Mage::helper('amazonproducts_amazonproducts')->__('Manage Label / Options'),
                'content'   => $this->getLayout()->createBlock(
                    'amazonproducts_amazonproducts/adminhtml_amazonproduct_attribute_edit_tab_options'
                )
                ->toHtml(),
            )
        );
        return parent::_beforeToHtml();
    }
}

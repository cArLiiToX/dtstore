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
 * Amazon Product admin edit form
 *
 * @category    Amazon
 * @package     Amazon_Products
 * @author      Ultimate Module Creator
 */
class Amazon_Products_Block_Adminhtml_Amazon_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
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
        $this->_blockGroup = 'amazon_products';
        $this->_controller = 'adminhtml_amazon';
        $this->_updateButton(
            'save',
            'label',
            Mage::helper('amazon_products')->__('Save Amazon Product')
        );
        $this->_updateButton(
            'delete',
            'label',
            Mage::helper('amazon_products')->__('Delete Amazon Product')
        );
        $this->_addButton(
            'saveandcontinue',
            array(
                'label'   => Mage::helper('amazon_products')->__('Save And Continue Edit'),
                'onclick' => 'saveAndContinueEdit()',
                'class'   => 'save',
            ),
            -100
        );
        $this->_formScripts[] = "
            function saveAndContinueEdit() {
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    /**
     * get the edit form header
     *
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getHeaderText()
    {
        if (Mage::registry('current_amazon') && Mage::registry('current_amazon')->getId()) {
            return Mage::helper('amazon_products')->__(
                "Edit Amazon Product '%s'",
                $this->escapeHtml(Mage::registry('current_amazon')->getName())
            );
        } else {
            return Mage::helper('amazon_products')->__('Add Amazon Product');
        }
    }
}

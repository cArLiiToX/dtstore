<?php
/**
 * Banners_Nikolai extension
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 * 
 * @category       Banners
 * @package        Banners_Nikolai
 * @copyright      Copyright (c) 2014
 * @license        http://opensource.org/licenses/mit-license.php MIT License
 */
/**
 * Banner DT Store admin edit form
 *
 * @category    Banners
 * @package     Banners_Nikolai
 * @author      Ultimate Module Creator
 */
class Banners_Nikolai_Block_Adminhtml_Bannerdt_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
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
        $this->_blockGroup = 'banners_nikolai';
        $this->_controller = 'adminhtml_bannerdt';
        $this->_updateButton(
            'save',
            'label',
            Mage::helper('banners_nikolai')->__('Save Banner DT Store')
        );
        $this->_updateButton(
            'delete',
            'label',
            Mage::helper('banners_nikolai')->__('Delete Banner DT Store')
        );
        $this->_addButton(
            'saveandcontinue',
            array(
                'label'   => Mage::helper('banners_nikolai')->__('Save And Continue Edit'),
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
        if (Mage::registry('current_bannerdt') && Mage::registry('current_bannerdt')->getId()) {
            return Mage::helper('banners_nikolai')->__(
                "Edit Banner DT Store '%s'",
                $this->escapeHtml(Mage::registry('current_bannerdt')->getNombre())
            );
        } else {
            return Mage::helper('banners_nikolai')->__('Add Banner DT Store');
        }
    }
}

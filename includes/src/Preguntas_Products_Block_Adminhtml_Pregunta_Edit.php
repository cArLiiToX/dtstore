<?php
/**
 * Preguntas_Products extension
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 * 
 * @category       Preguntas
 * @package        Preguntas_Products
 * @copyright      Copyright (c) 2014
 * @license        http://opensource.org/licenses/mit-license.php MIT License
 */
/**
 * Preguntas Product admin edit form
 *
 * @category    Preguntas
 * @package     Preguntas_Products
 * @author      Ultimate Module Creator
 */
class Preguntas_Products_Block_Adminhtml_Pregunta_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
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
        $this->_blockGroup = 'preguntas_products';
        $this->_controller = 'adminhtml_pregunta';
        $this->_updateButton(
            'save',
            'label',
            Mage::helper('preguntas_products')->__('Save Preguntas Product')
        );
        $this->_updateButton(
            'delete',
            'label',
            Mage::helper('preguntas_products')->__('Delete Preguntas Product')
        );
        $this->_addButton(
            'saveandcontinue',
            array(
                'label'   => Mage::helper('preguntas_products')->__('Save And Continue Edit'),
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
        if (Mage::registry('current_pregunta') && Mage::registry('current_pregunta')->getId()) {
            return Mage::helper('preguntas_products')->__(
                "Edit Preguntas Product '%s'",
                $this->escapeHtml(Mage::registry('current_pregunta')->getName())
            );
        } else {
            return Mage::helper('preguntas_products')->__('Add Preguntas Product');
        }
    }
}

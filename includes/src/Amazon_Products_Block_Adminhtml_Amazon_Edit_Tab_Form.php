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
 * Amazon Product edit form tab
 *
 * @category    Amazon
 * @package     Amazon_Products
 * @author      Ultimate Module Creator
 */
class Amazon_Products_Block_Adminhtml_Amazon_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the form
     *
     * @access protected
     * @return Amazon_Products_Block_Adminhtml_Amazon_Edit_Tab_Form
     * @author Ultimate Module Creator
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('amazon_');
        $form->setFieldNameSuffix('amazon');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'amazon_form',
            array('legend' => Mage::helper('amazon_products')->__('Amazon Product'))
        );

        $fieldset->addField(
            'name',
            'text',
            array(
                'label' => Mage::helper('amazon_products')->__('Name'),
                'name'  => 'name',
            'note'	=> $this->__('Nombre del Producto, Nombre de Guia.'),
            'required'  => true,
            'class' => 'required-entry',

           )
        );

        $fieldset->addField(
            'link',
            'text',
            array(
                'label' => Mage::helper('amazon_products')->__('Amazon Link'),
                'name'  => 'link',
            'note'	=> $this->__('Colocar aqui el link de Amazon. Ejemplo: http://www.amazon.com/PlayStation-4-Console/dp/B00BGA9WK2'),
            'required'  => true,
            'class' => 'required-entry',

           )
        );
        $fieldset->addField(
            'status',
            'select',
            array(
                'label'  => Mage::helper('amazon_products')->__('Status'),
                'name'   => 'status',
                'values' => array(
                    array(
                        'value' => 1,
                        'label' => Mage::helper('amazon_products')->__('Enabled'),
                    ),
                    array(
                        'value' => 0,
                        'label' => Mage::helper('amazon_products')->__('Disabled'),
                    ),
                ),
            )
        );
        $formValues = Mage::registry('current_amazon')->getDefaultValues();
        if (!is_array($formValues)) {
            $formValues = array();
        }
        if (Mage::getSingleton('adminhtml/session')->getAmazonData()) {
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getAmazonData());
            Mage::getSingleton('adminhtml/session')->setAmazonData(null);
        } elseif (Mage::registry('current_amazon')) {
            $formValues = array_merge($formValues, Mage::registry('current_amazon')->getData());
        }
        $form->setValues($formValues);
        return parent::_prepareForm();
    }
}

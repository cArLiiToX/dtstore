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
 * Banner DT Store edit form tab
 *
 * @category    Banners
 * @package     Banners_Nikolai
 * @author      Ultimate Module Creator
 */
class Banners_Nikolai_Block_Adminhtml_Bannerdt_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the form
     *
     * @access protected
     * @return Banners_Nikolai_Block_Adminhtml_Bannerdt_Edit_Tab_Form
     * @author Ultimate Module Creator
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('bannerdt_');
        $form->setFieldNameSuffix('bannerdt');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'bannerdt_form',
            array('legend' => Mage::helper('banners_nikolai')->__('Banner DT Store'))
        );
        $fieldset->addType(
            'image',
            Mage::getConfig()->getBlockClassName('banners_nikolai/adminhtml_bannerdt_helper_image')
        );

        $fieldset->addField(
            'nombre',
            'text',
            array(
                'label' => Mage::helper('banners_nikolai')->__('Nombre'),
                'name'  => 'nombre',
            'note'	=> $this->__('Nombre del Banner'),
            'required'  => true,
            'class' => 'required-entry',

           )
        );

        $fieldset->addField(
            'image',
            'image',
            array(
                'label' => Mage::helper('banners_nikolai')->__('Imagen'),
                'name'  => 'image',
            'note'	=> $this->__('Imagen de Banner'),

           )
        );

        $fieldset->addField(
            'onclick',
            'text',
            array(
                'label' => Mage::helper('banners_nikolai')->__('Onclick Function'),
                'name'  => 'onclick',

           )
        );

        $fieldset->addField(
            'ordenamiento',
            'text',
            array(
                'label' => Mage::helper('banners_nikolai')->__('Ordenamiento'),
                'name'  => 'ordenamiento',
            'note'	=> $this->__('Seleccione la posicion de la imagen del slider.'),
            'required'  => true,
            'class' => 'required-entry',

           )
        );

        $fieldset->addField(
            'enlace',
            'text',
            array(
                'label' => Mage::helper('banners_nikolai')->__('Enlace'),
                'name'  => 'enlace',
            'note'	=> $this->__('Enlace a donde va a redireccionar.'),
            'required'  => true,
            'class' => 'required-entry',

           )
        );

        $fieldset->addField(
            'seccion',
            'select',
            array(
                'label' => Mage::helper('banners_nikolai')->__('Seccion Banner'),
                'name'  => 'seccion',
            'note'	=> $this->__('Seleccione en que seccion va a ir el banner que se va a cargar.'),
            'required'  => true,
            'class' => 'required-entry',

            'values'=> Mage::getModel('banners_nikolai/bannerdt_attribute_source_seccion')->getAllOptions(true),
           )
        );
        $fieldset->addField(
            'status',
            'select',
            array(
                'label'  => Mage::helper('banners_nikolai')->__('Status'),
                'name'   => 'status',
                'values' => array(
                    array(
                        'value' => 1,
                        'label' => Mage::helper('banners_nikolai')->__('Enabled'),
                    ),
                    array(
                        'value' => 0,
                        'label' => Mage::helper('banners_nikolai')->__('Disabled'),
                    ),
                ),
            )
        );
        $formValues = Mage::registry('current_bannerdt')->getDefaultValues();
        if (!is_array($formValues)) {
            $formValues = array();
        }
        if (Mage::getSingleton('adminhtml/session')->getBannerdtData()) {
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getBannerdtData());
            Mage::getSingleton('adminhtml/session')->setBannerdtData(null);
        } elseif (Mage::registry('current_bannerdt')) {
            $formValues = array_merge($formValues, Mage::registry('current_bannerdt')->getData());
        }
        $form->setValues($formValues);
        return parent::_prepareForm();
    }
}

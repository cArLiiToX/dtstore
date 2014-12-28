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
 * Preguntas Product edit form tab
 *
 * @category    Preguntas
 * @package     Preguntas_Products
 * @author      Ultimate Module Creator
 */
class Preguntas_Products_Block_Adminhtml_Pregunta_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the form
     *
     * @access protected
     * @return Preguntas_Products_Block_Adminhtml_Pregunta_Edit_Tab_Form
     * @author Ultimate Module Creator
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('pregunta_');
        $form->setFieldNameSuffix('pregunta');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'pregunta_form',
            array('legend' => Mage::helper('preguntas_products')->__('Preguntas Product'))
        );

        $fieldset->addField(
            'name',
            'text',
            array(
                'label' => Mage::helper('preguntas_products')->__('Nombre'),
                'name'  => 'name',
            'note'	=> $this->__('Nombre de la persona que coloca la pregunta.'),
            'required'  => true,
            'class' => 'required-entry',

           )
        );

        $fieldset->addField(
            'email',
            'text',
            array(
                'label' => Mage::helper('preguntas_products')->__('Email'),
                'name'  => 'email',
            'note'	=> $this->__('Email del que envia la pregunta.'),

           )
        );

        $fieldset->addField(
            'pregunta',
            'text',
            array(
                'label' => Mage::helper('preguntas_products')->__('Pregunta'),
                'name'  => 'pregunta',
            'note'	=> $this->__('Contenido de la Pregunta'),

           )
        );

        $fieldset->addField(
            'respuesta',
            'text',
            array(
                'label' => Mage::helper('preguntas_products')->__('Respuesta'),
                'name'  => 'respuesta',

           )
        );

        $fieldset->addField(
            'contestada',
            'select',
            array(
                'label' => Mage::helper('preguntas_products')->__('Contestada'),
                'name'  => 'contestada',
            'required'  => true,
            'class' => 'required-entry',

            'values'=> array(
                array(
                    'value' => 1,
                    'label' => Mage::helper('preguntas_products')->__('Yes'),
                ),
                array(
                    'value' => 0,
                    'label' => Mage::helper('preguntas_products')->__('No'),
                ),
            ),
           )
        );
        $fieldset->addField(
            'status',
            'select',
            array(
                'label'  => Mage::helper('preguntas_products')->__('Status'),
                'name'   => 'status',
                'values' => array(
                    array(
                        'value' => 1,
                        'label' => Mage::helper('preguntas_products')->__('Enabled'),
                    ),
                    array(
                        'value' => 0,
                        'label' => Mage::helper('preguntas_products')->__('Disabled'),
                    ),
                ),
            )
        );
        $formValues = Mage::registry('current_pregunta')->getDefaultValues();
        if (!is_array($formValues)) {
            $formValues = array();
        }
        if (Mage::getSingleton('adminhtml/session')->getPreguntaData()) {
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getPreguntaData());
            Mage::getSingleton('adminhtml/session')->setPreguntaData(null);
        } elseif (Mage::registry('current_pregunta')) {
            $formValues = array_merge($formValues, Mage::registry('current_pregunta')->getData());
        }
        $form->setValues($formValues);
        return parent::_prepareForm();
    }
}

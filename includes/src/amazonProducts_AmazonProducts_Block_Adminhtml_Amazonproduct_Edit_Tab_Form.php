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
 * Amazon Product edit form tab
 *
 * @category    amazonProducts
 * @package     amazonProducts_AmazonProducts
 * @author      Ultimate Module Creator
 */
class amazonProducts_AmazonProducts_Block_Adminhtml_Amazonproduct_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the form
     *
     * @access protected
     * @return amazonProducts_AmazonProducts_Block_Adminhtml_Amazonproduct_Edit_Tab_Form
     * @author Ultimate Module Creator
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('amazonproduct_');
        $form->setFieldNameSuffix('amazonproduct');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'amazonproduct_form',
            array('legend' => Mage::helper('amazonproducts_amazonproducts')->__('Amazon Product'))
        );
        if (!$this->getAmazonproduct()->getId()) {
            $parentId = $this->getRequest()->getParam('parent');
            if (!$parentId) {
                $parentId = Mage::helper('amazonproducts_amazonproducts/amazonproduct')->getRootAmazonproductId();
            }
            $fieldset->addField(
                'path',
                'hidden',
                array(
                    'name'  => 'path',
                    'value' => $parentId
                )
            );
        } else {
            $fieldset->addField(
                'id',
                'hidden',
                array(
                    'name'  => 'id',
                    'value' => $this->getAmazonproduct()->getId()
                )
            );
            $fieldset->addField(
                'path',
                'hidden',
                array(
                    'name'  => 'path',
                    'value' => $this->getAmazonproduct()->getPath()
                )
            );
        }

        $fieldset->addField(
            'name',
            'text',
            array(
                'label' => Mage::helper('amazonproducts_amazonproducts')->__('Name'),
                'name'  => 'name',
            'note'	=> $this->__('Nombre del Producto, Nombre de Guia.'),
            'required'  => true,
            'class' => 'required-entry',

           )
        );

        $fieldset->addField(
            'enabled',
            'select',
            array(
                'label' => Mage::helper('amazonproducts_amazonproducts')->__('Enabled'),
                'name'  => 'enabled',
            'note'	=> $this->__('Habilitar o deshabilitar el producto asociado de amazon.'),
            'required'  => true,
            'class' => 'required-entry',

            'values'=> array(
                array(
                    'value' => 1,
                    'label' => Mage::helper('amazonproducts_amazonproducts')->__('Yes'),
                ),
                array(
                    'value' => 0,
                    'label' => Mage::helper('amazonproducts_amazonproducts')->__('No'),
                ),
            ),
           )
        );

        $fieldset->addField(
            'link',
            'text',
            array(
                'label' => Mage::helper('amazonproducts_amazonproducts')->__('Amazon Link'),
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
                'label'  => Mage::helper('amazonproducts_amazonproducts')->__('Status'),
                'name'   => 'status',
                'values' => array(
                    array(
                        'value' => 1,
                        'label' => Mage::helper('amazonproducts_amazonproducts')->__('Enabled'),
                    ),
                    array(
                        'value' => 0,
                        'label' => Mage::helper('amazonproducts_amazonproducts')->__('Disabled'),
                    ),
                ),
            )
        );
        if (Mage::app()->isSingleStoreMode()) {
            $fieldset->addField(
                'store_id',
                'hidden',
                array(
                    'name'      => 'stores[]',
                    'value'     => Mage::app()->getStore(true)->getId()
                )
            );
            Mage::registry('current_amazonproduct')->setStoreId(Mage::app()->getStore(true)->getId());
        }
        $form->addValues($this->getAmazonproduct()->getData());
        return parent::_prepareForm();
    }

    /**
     * get the current amazon product
     *
     * @access public
     * @return amazonProducts_AmazonProducts_Model_Amazonproduct
     */
    public function getAmazonproduct()
    {
        return Mage::registry('amazonproduct');
    }
}

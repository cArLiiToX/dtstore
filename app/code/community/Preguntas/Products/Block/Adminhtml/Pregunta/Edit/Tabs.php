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
 * Preguntas Product admin edit tabs
 *
 * @category    Preguntas
 * @package     Preguntas_Products
 * @author      Ultimate Module Creator
 */
class Preguntas_Products_Block_Adminhtml_Pregunta_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    /**
     * Initialize Tabs
     *
     * @access public
     * @author Ultimate Module Creator
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('pregunta_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('preguntas_products')->__('Preguntas Product'));
    }

    /**
     * before render html
     *
     * @access protected
     * @return Preguntas_Products_Block_Adminhtml_Pregunta_Edit_Tabs
     * @author Ultimate Module Creator
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'form_pregunta',
            array(
                'label'   => Mage::helper('preguntas_products')->__('Preguntas Product'),
                'title'   => Mage::helper('preguntas_products')->__('Preguntas Product'),
                'content' => $this->getLayout()->createBlock(
                    'preguntas_products/adminhtml_pregunta_edit_tab_form'
                )
                ->toHtml(),
            )
        );
        $this->addTab(
            'products',
            array(
                'label' => Mage::helper('preguntas_products')->__('Associated products'),
                'url'   => $this->getUrl('*/*/products', array('_current' => true)),
                'class' => 'ajax'
            )
        );
        return parent::_beforeToHtml();
    }

    /**
     * Retrieve preguntas product entity
     *
     * @access public
     * @return Preguntas_Products_Model_Pregunta
     * @author Ultimate Module Creator
     */
    public function getPregunta()
    {
        return Mage::registry('current_pregunta');
    }
}

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
 * Preguntas Product tab on product edit form
 *
 * @category    Preguntas
 * @package     Preguntas_Products
 * @author      Ultimate Module Creator
 */
class Preguntas_Products_Block_Adminhtml_Catalog_Product_Edit_Tab_Pregunta extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * Set grid params
     *
     * @access public
     * @author Ultimate Module Creator
     */

    public function __construct()
    {
        parent::__construct();
        $this->setId('pregunta_grid');
        $this->setDefaultSort('position');
        $this->setDefaultDir('ASC');
        $this->setUseAjax(true);
        if ($this->getProduct()->getId()) {
            $this->setDefaultFilter(array('in_preguntas'=>1));
        }
    }

    /**
     * prepare the pregunta collection
     *
     * @access protected
     * @return Preguntas_Products_Block_Adminhtml_Catalog_Product_Edit_Tab_Pregunta
     * @author Ultimate Module Creator
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel('preguntas_products/pregunta_collection');
        if ($this->getProduct()->getId()) {
            $constraint = 'related.product_id='.$this->getProduct()->getId();
        } else {
            $constraint = 'related.product_id=0';
        }
        $collection->getSelect()->joinLeft(
            array('related' => $collection->getTable('preguntas_products/pregunta_product')),
            'related.pregunta_id=main_table.entity_id AND '.$constraint,
            array('position')
        );
        $this->setCollection($collection);
        parent::_prepareCollection();
        return $this;
    }

    /**
     * prepare mass action grid
     *
     * @access protected
     * @return Preguntas_Products_Block_Adminhtml_Catalog_Product_Edit_Tab_Pregunta
     * @author Ultimate Module Creator
     */
    protected function _prepareMassaction()
    {
        return $this;
    }

    /**
     * prepare the grid columns
     *
     * @access protected
     * @return Preguntas_Products_Block_Adminhtml_Catalog_Product_Edit_Tab_Pregunta
     * @author Ultimate Module Creator
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'in_preguntas',
            array(
                'header_css_class'  => 'a-center',
                'type'  => 'checkbox',
                'name'  => 'in_preguntas',
                'values'=> $this->_getSelectedPreguntas(),
                'align' => 'center',
                'index' => 'entity_id'
            )
        );
        $this->addColumn(
            'name',
            array(
                'header' => Mage::helper('preguntas_products')->__('Nombre'),
                'align'  => 'left',
                'index'  => 'name',
                'renderer' => 'preguntas_products/adminhtml_helper_column_renderer_relation',
                'params' => array(
                    'id' => 'getId'
                ),
                'base_link' => 'adminhtml/products_pregunta/edit',
            )
        );
        $this->addColumn(
            'position',
            array(
                'header'         => Mage::helper('preguntas_products')->__('Position'),
                'name'           => 'position',
                'width'          => 60,
                'type'           => 'number',
                'validate_class' => 'validate-number',
                'index'          => 'position',
                'editable'       => true,
            )
        );
        return parent::_prepareColumns();
    }

    /**
     * Retrieve selected preguntas
     *
     * @access protected
     * @return array
     * @author Ultimate Module Creator
     */
    protected function _getSelectedPreguntas()
    {
        $preguntas = $this->getProductPreguntas();
        if (!is_array($preguntas)) {
            $preguntas = array_keys($this->getSelectedPreguntas());
        }
        return $preguntas;
    }

    /**
     * Retrieve selected preguntas
     *
     * @access protected
     * @return array
     * @author Ultimate Module Creator
     */
    public function getSelectedPreguntas()
    {
        $preguntas = array();
        //used helper here in order not to override the product model
        $selected = Mage::helper('preguntas_products/product')->getSelectedPreguntas(Mage::registry('current_product'));
        if (!is_array($selected)) {
            $selected = array();
        }
        foreach ($selected as $pregunta) {
            $preguntas[$pregunta->getId()] = array('position' => $pregunta->getPosition());
        }
        return $preguntas;
    }

    /**
     * get row url
     *
     * @access public
     * @param Preguntas_Products_Model_Pregunta
     * @return string
     * @author Ultimate Module Creator
     */
    public function getRowUrl($item)
    {
        return '#';
    }

    /**
     * get grid url
     *
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getGridUrl()
    {
        return $this->getUrl(
            '*/*/preguntasGrid',
            array(
                'id'=>$this->getProduct()->getId()
            )
        );
    }

    /**
     * get the current product
     *
     * @access public
     * @return Mage_Catalog_Model_Product
     * @author Ultimate Module Creator
     */
    public function getProduct()
    {
        return Mage::registry('current_product');
    }

    /**
     * Add filter
     *
     * @access protected
     * @param object $column
     * @return Preguntas_Products_Block_Adminhtml_Catalog_Product_Edit_Tab_Pregunta
     * @author Ultimate Module Creator
     */
    protected function _addColumnFilterToCollection($column)
    {
        if ($column->getId() == 'in_preguntas') {
            $preguntaIds = $this->_getSelectedPreguntas();
            if (empty($preguntaIds)) {
                $preguntaIds = 0;
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('entity_id', array('in'=>$preguntaIds));
            } else {
                if ($preguntaIds) {
                    $this->getCollection()->addFieldToFilter('entity_id', array('nin'=>$preguntaIds));
                }
            }
        } else {
            parent::_addColumnFilterToCollection($column);
        }
        return $this;
    }
}

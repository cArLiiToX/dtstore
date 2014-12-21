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
 * Amazon Product tab on product edit form
 *
 * @category    Amazon
 * @package     Amazon_Products
 * @author      Ultimate Module Creator
 */
class Amazon_Products_Block_Adminhtml_Catalog_Product_Edit_Tab_Amazon extends Mage_Adminhtml_Block_Widget_Grid
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
        $this->setId('amazon_grid');
        $this->setDefaultSort('position');
        $this->setDefaultDir('ASC');
        $this->setUseAjax(true);
        if ($this->getProduct()->getId()) {
            $this->setDefaultFilter(array('in_amazons'=>1));
        }
    }

    /**
     * prepare the amazon collection
     *
     * @access protected
     * @return Amazon_Products_Block_Adminhtml_Catalog_Product_Edit_Tab_Amazon
     * @author Ultimate Module Creator
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel('amazon_products/amazon_collection');
        if ($this->getProduct()->getId()) {
            $constraint = 'related.product_id='.$this->getProduct()->getId();
        } else {
            $constraint = 'related.product_id=0';
        }
        $collection->getSelect()->joinLeft(
            array('related' => $collection->getTable('amazon_products/amazon_product')),
            'related.amazon_id=main_table.entity_id AND '.$constraint,
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
     * @return Amazon_Products_Block_Adminhtml_Catalog_Product_Edit_Tab_Amazon
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
     * @return Amazon_Products_Block_Adminhtml_Catalog_Product_Edit_Tab_Amazon
     * @author Ultimate Module Creator
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'in_amazons',
            array(
                'header_css_class'  => 'a-center',
                'type'  => 'checkbox',
                'name'  => 'in_amazons',
                'values'=> $this->_getSelectedAmazons(),
                'align' => 'center',
                'index' => 'entity_id'
            )
        );
        $this->addColumn(
            'name',
            array(
                'header' => Mage::helper('amazon_products')->__('Name'),
                'align'  => 'left',
                'index'  => 'name',
                'renderer' => 'amazon_products/adminhtml_helper_column_renderer_relation',
                'params' => array(
                    'id' => 'getId'
                ),
                'base_link' => 'adminhtml/products_amazon/edit',
            )
        );
        $this->addColumn(
            'position',
            array(
                'header'         => Mage::helper('amazon_products')->__('Position'),
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
     * Retrieve selected amazons
     *
     * @access protected
     * @return array
     * @author Ultimate Module Creator
     */
    protected function _getSelectedAmazons()
    {
        $amazons = $this->getProductAmazons();
        if (!is_array($amazons)) {
            $amazons = array_keys($this->getSelectedAmazons());
        }
        return $amazons;
    }

    /**
     * Retrieve selected amazons
     *
     * @access protected
     * @return array
     * @author Ultimate Module Creator
     */
    public function getSelectedAmazons()
    {
        $amazons = array();
        //used helper here in order not to override the product model
        $selected = Mage::helper('amazon_products/product')->getSelectedAmazons(Mage::registry('current_product'));
        if (!is_array($selected)) {
            $selected = array();
        }
        foreach ($selected as $amazon) {
            $amazons[$amazon->getId()] = array('position' => $amazon->getPosition());
        }
        return $amazons;
    }

    /**
     * get row url
     *
     * @access public
     * @param Amazon_Products_Model_Amazon
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
            '*/*/amazonsGrid',
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
     * @return Amazon_Products_Block_Adminhtml_Catalog_Product_Edit_Tab_Amazon
     * @author Ultimate Module Creator
     */
    protected function _addColumnFilterToCollection($column)
    {
        if ($column->getId() == 'in_amazons') {
            $amazonIds = $this->_getSelectedAmazons();
            if (empty($amazonIds)) {
                $amazonIds = 0;
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('entity_id', array('in'=>$amazonIds));
            } else {
                if ($amazonIds) {
                    $this->getCollection()->addFieldToFilter('entity_id', array('nin'=>$amazonIds));
                }
            }
        } else {
            parent::_addColumnFilterToCollection($column);
        }
        return $this;
    }
}

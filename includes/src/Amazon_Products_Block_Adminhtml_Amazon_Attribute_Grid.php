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
 * Amazon Product attributes grid
 *
 * @category    Amazon
 * @package     Amazon_Products
 * @author      Ultimate Module Creator
 */
class Amazon_Products_Block_Adminhtml_Amazon_Attribute_Grid extends Mage_Eav_Block_Adminhtml_Attribute_Grid_Abstract
{
    /**
     * Prepare amazon product attributes grid collection object
     *
     * @access protected
     * @return Amazon_Products_Block_Adminhtml_Amazon_Attribute_Grid
     * @author Ultimate Module Creator
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel('amazon_products/amazon_attribute_collection')
            ->addVisibleFilter();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * Prepare amazon attributes grid columns
     *
     * @access protected
     * @return Amazon_Products_Block_Adminhtml_Amazon_Attribute_Grid
     * @author Ultimate Module Creator
     */
    protected function _prepareColumns()
    {
        parent::_prepareColumns();
        $this->addColumnAfter(
            'is_global',
            array(
                'header'   => Mage::helper('amazon_products')->__('Scope'),
                'sortable' => true,
                'index'    => 'is_global',
                'type'     => 'options',
                'options'  => array(
                    Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE   =>
                        Mage::helper('amazon_products')->__('Store View'),
                    Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_WEBSITE =>
                        Mage::helper('amazon_products')->__('Website'),
                    Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL  =>
                        Mage::helper('amazon_products')->__('Global'),
                ),
                'align' => 'center',
            ),
            'is_user_defined'
        );
        return $this;
    }
}

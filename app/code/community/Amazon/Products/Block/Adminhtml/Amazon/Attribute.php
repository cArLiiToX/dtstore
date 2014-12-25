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
 * Amazon Product admin attribute block
 *
 * @category    Amazon
 * @package     Amazon_Products
 * @author      Ultimate Module Creator
 */
class Amazon_Products_Block_Adminhtml_Amazon_Attribute extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    /**
     * constructor
     *
     * @access public
     * @author Ultimate Module Creator
     */
    public function __construct()
    {
        $this->_controller = 'adminhtml_amazon_attribute';
        $this->_blockGroup = 'amazon_products';
        $this->_headerText = Mage::helper('amazon_products')->__('Manage Amazon Product Attributes');
        parent::__construct();
        $this->_updateButton(
            'add',
            'label',
            Mage::helper('amazon_products')->__('Add New Amazon Product Attribute')
        );
    }
}

<?php
/**
 * amazon_products_Amazon Products extension
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 * 
 * @category       amazon_products
 * @package        amazon_products_Amazon Products
 * @copyright      Copyright (c) 2014
 * @license        http://opensource.org/licenses/mit-license.php MIT License
 */
/**
 * Attribute resource model
 *
 * @category    amazon_products
 * @package     amazon_products_Amazon Products
 * @author      Ultimate Module Creator
 */
class amazon_products_Amazon Products_Model_Resource_Eav_Attribute extends Mage_Eav_Model_Entity_Attribute
{
    const MODULE_NAME   = 'amazon_products_Amazon Products';
    const ENTITY        = 'amazon_products_amazon products_eav_attribute';

    protected $_eventPrefix = 'amazon_products_amazon products_entity_attribute';
    protected $_eventObject = 'attribute';

    /**
     * Array with labels
     *
     * @var array
     */
    static protected $_labels = null;

    /**
     * constructor
     *
     * @access protected
     * @return void
     * @author Ultimate Module Creator
     */
    protected function _construct()
    {
        $this->_init('amazon_products_amazon products/attribute');
    }

    /**
     * check if scope is store view
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function isScopeStore()
    {
        return $this->getIsGlobal() == Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE;
    }

    /**
     * check if scope is website
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function isScopeWebsite()
    {
        return $this->getIsGlobal() == Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_WEBSITE;
    }

    /**
     * check if scope is global
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function isScopeGlobal()
    {
        return (!$this->isScopeStore() && !$this->isScopeWebsite());
    }

    /**
     * get backend input type
     *
     * @access public
     * @param string $type
     * @return string
     * @author Ultimate Module Creator
     */
    public function getBackendTypeByInput($type)
    {
        switch ($type) {
            case 'file':
                //intentional fallthrough
            case 'image':
                return 'varchar';
                break;
            default:
                return parent::getBackendTypeByInput($type);
            break;
        }
    }

    /**
     * don't delete system attributes
     *
     * @access public
     * @param string $type
     * @return string
     * @author Ultimate Module Creator
     */
    protected function _beforeDelete()
    {
        if (!$this->getIsUserDefined()) {
            throw new Mage_Core_Exception(
                Mage::helper('amazon_products_amazon products')->__('This attribute is not deletable')
            );
        }
        return parent::_beforeDelete();
    }
}

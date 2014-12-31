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
 * Amazon Products setup
 *
 * @category    amazon_products
 * @package     amazon_products_Amazon Products
 * @author      Ultimate Module Creator
 */
class amazon_products_Amazon Products_Model_Resource_Setup extends Mage_Catalog_Model_Resource_Setup
{

    /**
     * get the default entities for amazon products module - used at installation
     *
     * @access public
     * @return array()
     * @author Ultimate Module Creator
     */
    public function getDefaultEntities()
    {
        $entities = array();
        $entities['amazon_products_amazon products_amazonproduct'] = array(
            'entity_model'                  => 'amazon_products_amazon products/amazonproduct',
            'attribute_model'               => 'amazon_products_amazon products/resource_eav_attribute',
            'table'                         => 'amazon_products_amazon products/amazonproduct',
            'additional_attribute_table'    => 'amazon_products_amazon products/eav_attribute',
            'entity_attribute_collection'   => 'amazon_products_amazon products/amazonproduct_attribute_collection',
            'attributes'                    => array(
                    'name' => array(
                        'group'          => 'General',
                        'type'           => 'varchar',
                        'backend'        => '',
                        'frontend'       => '',
                        'label'          => 'Name',
                        'input'          => 'text',
                        'source'         => '',
                        'global'         => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
                        'required'       => '1',
                        'user_defined'   => false,
                        'default'        => '',
                        'unique'         => false,
                        'position'       => '10',
                        'note'           => 'Nombre del Producto, Nombre de Guia.',
                        'visible'        => '1',
                        'wysiwyg_enabled'=> '0',
                    ),
                    'enabled' => array(
                        'group'          => 'General',
                        'type'           => 'int',
                        'backend'        => '',
                        'frontend'       => '',
                        'label'          => 'Enabled',
                        'input'          => 'select',
                        'source'         => 'eav/entity_attribute_source_boolean',
                        'global'         => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
                        'required'       => '1',
                        'user_defined'   => true,
                        'default'        => '',
                        'unique'         => false,
                        'position'       => '20',
                        'note'           => 'Habilitar o deshabilitar el producto asociado de amazon.',
                        'visible'        => '1',
                        'wysiwyg_enabled'=> '0',
                    ),
                    'link' => array(
                        'group'          => 'General',
                        'type'           => 'varchar',
                        'backend'        => '',
                        'frontend'       => '',
                        'label'          => 'Amazon Link',
                        'input'          => 'text',
                        'source'         => '',
                        'global'         => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
                        'required'       => '1',
                        'user_defined'   => true,
                        'default'        => '',
                        'unique'         => false,
                        'position'       => '30',
                        'note'           => 'Colocar aqui el link de Amazon. Ejemplo: http://www.amazon.com/PlayStation-4-Console/dp/B00BGA9WK2',
                        'visible'        => '1',
                        'wysiwyg_enabled'=> '0',
                    ),
                    'status' => array(
                        'group'          => 'General',
                        'type'           => 'int',
                        'backend'        => '',
                        'frontend'       => '',
                        'label'          => 'Enabled',
                        'input'          => 'select',
                        'source'         => 'eav/entity_attribute_source_boolean',
                        'global'         => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
                        'required'       => '',
                        'user_defined'   => false,
                        'default'        => '1',
                        'unique'         => false,
                        'position'       => '40',
                        'note'           => '',
                        'visible'        => '1',
                        'wysiwyg_enabled'=> '0',
                    ),
                    'parent_id' => array(
                        'group'          => 'General',
                        'type'           => 'static',
                        'backend'        => '',
                        'frontend'       => '',
                        'label'          => 'Parent id',
                        'input'          => 'text',
                        'source'         => '',
                        'global'         => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
                        'required'       => '',
                        'user_defined'   => false,
                        'default'        => '',
                        'unique'         => false,
                        'position'       => '0',
                        'note'           => '',
                        'visible'        => '0',
                        'wysiwyg_enabled'=> '0',
                    ),
                    'path' => array(
                        'group'          => 'General',
                        'type'           => 'static',
                        'backend'        => '',
                        'frontend'       => '',
                        'label'          => 'Path',
                        'input'          => 'text',
                        'source'         => '',
                        'global'         => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
                        'required'       => '',
                        'user_defined'   => false,
                        'default'        => '',
                        'unique'         => false,
                        'position'       => '0',
                        'note'           => '',
                        'visible'        => '0',
                        'wysiwyg_enabled'=> '0',
                    ),
                    'position' => array(
                        'group'          => 'General',
                        'type'           => 'static',
                        'backend'        => '',
                        'frontend'       => '',
                        'label'          => 'Position',
                        'input'          => 'text',
                        'source'         => '',
                        'global'         => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
                        'required'       => '',
                        'user_defined'   => false,
                        'default'        => '',
                        'unique'         => false,
                        'position'       => '0',
                        'note'           => '',
                        'visible'        => '0',
                        'wysiwyg_enabled'=> '0',
                    ),
                    'level' => array(
                        'group'          => 'General',
                        'type'           => 'static',
                        'backend'        => '',
                        'frontend'       => '',
                        'label'          => 'Level',
                        'input'          => 'text',
                        'source'         => '',
                        'global'         => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
                        'required'       => '',
                        'user_defined'   => false,
                        'default'        => '',
                        'unique'         => false,
                        'position'       => '0',
                        'note'           => '',
                        'visible'        => '0',
                        'wysiwyg_enabled'=> '0',
                    ),
                    'children_count' => array(
                        'group'          => 'General',
                        'type'           => 'static',
                        'backend'        => '',
                        'frontend'       => '',
                        'label'          => 'Children count',
                        'input'          => 'text',
                        'source'         => '',
                        'global'         => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
                        'required'       => '',
                        'user_defined'   => false,
                        'default'        => '',
                        'unique'         => false,
                        'position'       => '0',
                        'note'           => '',
                        'visible'        => '0',
                        'wysiwyg_enabled'=> '0',
                    ),

                )
         );
        return $entities;
    }
}

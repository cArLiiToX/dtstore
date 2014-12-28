<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @category   BL
 * @package    BL_CustomGrid
 * @copyright  Copyright (c) 2014 Benoît Leulliette <benoit.leulliette@gmail.com>
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class BL_CustomGrid_Model_Grid_Type_Cms_Page extends BL_CustomGrid_Model_Grid_Type_Abstract
{
    protected function _getSupportedBlockTypes()
    {
        return array('adminhtml/cms_page_grid');
    }
    
    protected function _getColumnsLockedValues($blockType)
    {
        return array(
            'store_code' => array(
                'renderer' => '',
                'config_values' => array(
                    'filter' => false,
                    'sortable' => false
                ),
            ),
            '_first_store_id' => array(
                'renderer' => '',
                'config_values' => array(
                    'filter' => false,
                    'sortable' => false
                ),
            ),
        );
    }
    
    protected function _getBaseEditableFields($blockType)
    {
        $helper = Mage::helper('cms');
        
        $fields = array(
            'title' => array(
                'type'     => 'text',
                'required' => true,
            ),
            'identifier' => array(
                'type'       => 'text',
                'required'   => true,
                'form_class' => 'validate-identifier',
                'form_note'  => $helper->__('Relative to Website Base URL'),
            ),
            'root_template' => array(
                'type'        => 'select',
                'form_values' => Mage::getSingleton('page/source_layout')->toOptionArray(),
                'required'    => true,
            ),
            'is_active' => array(
                'type'          => 'select',
                'form_options'  => Mage::getModel('cms/page')->getAvailableStatuses(),
                'required'      => true,
            ),
            'meta_keywords' => array(
                'type'          => 'textarea',
                'in_grid'       => false,
                'form_label'    => $helper->__('Meta Keywords'),
                'window_height' => 310,
            ),
            'meta_description' => array(
                'type'          => 'textarea',
                'in_grid'       => false,
                'form_label'    => $helper->__('Meta Description'),
                'window_height' => 310,
            ),
            'content_heading' => array(),
            'content' => array(
                'type'         => 'editor',
                'required'     => true,
                'in_grid'      => false,
                'form_wysiwyg' => true,
                'form_label'   => $helper->__('Content'),
                'form_style'   => 'height:36em;',
            ),
            'layout_update_xml' => array(
                'type'       => 'textarea',
                'in_grid'    => false,
                'form_label' => $helper->__('Layout Update XML'),
                'form_style' => 'height:24em;',
            ),
            'custom_theme' => array(
                'type'        => 'select',
                'form_values' => Mage::getModel('core/design_source_design')->getAllOptions(),
            ),
            'custom_root_template' => array(
                'type'        => 'select',
                'form_values' => Mage::getSingleton('page/source_layout')->toOptionArray(true),
            ),
            'custom_layout_update_xml' => array(
                'type'       => 'textarea',
                'in_grid'    => false,
                'form_label' => $helper->__('Custom Layout Update XML'),
                'form_style' => 'height:24em;',
            ),
            'custom_theme_from' => array(
                'type' => 'date',
            ),
            'custom_theme_to' => array(
                'type' => 'date',
            ),
        );
        
        if (!Mage::app()->isSingleStoreMode()) {
            $stores = Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true);
            
            $fields['store_id'] = array(
                'type'              => 'multiselect',
                'required'          => true,
                'form_values'       => $stores,
                'render_block_type' => 'customgrid/widget_grid_editor_renderer_static_store',
            );
        }
        
        return $fields;
    }
    
    protected function _getEntityRowIdentifiersKeys($blockType)
    {
        return array('page_id');
    }
    
    protected function _loadEditedEntity($blockType, BL_CustomGrid_Object $config, array $params, $entityId)
    {
        return Mage::getModel('cms/page')->load($entityId);
    }
    
    protected function _getLoadedEntityName($blockType, BL_CustomGrid_Object $config, array $params, $entity)
    {
        return $entity->getTitle();
    }
    
    protected function _getEditRequiredAclPermissions($blockType)
    {
        return 'cms/page/save';
    }
    
    protected function _applyEditedFieldValue($blockType, BL_CustomGrid_Object $config, array $params, $entity, $value)
    {
        if ($config['id'] == 'store_id') {
            $entity->setStores($value);
            return $this;
        }
        $entity->setStores($entity->getStoreId());
        return parent::_applyEditedFieldValue($blockType, $config, $params, $entity, $value);
    }
    
    protected function _getSavedFieldValueForRender($blockType, BL_CustomGrid_Object $config, array $params, $entity)
    {
        return ($config['id'] == 'store_id')
            ? $entity->getStores()
            : parent::_getSavedFieldValueForRender($blockType, $config, $params, $entity);
    }
}

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

class BL_CustomGrid_Model_Grid_Type_Cms_Block extends BL_CustomGrid_Model_Grid_Type_Abstract
{
    protected function _getSupportedBlockTypes()
    {
        return array('adminhtml/cms_block_grid');
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
                'form_class' => 'validate-xml-identifier',
            ),
            'is_active' => array(
                'type'         => 'select',
                'required'     => true,
                'form_options' => array(
                    '1' => $helper->__('Enabled'),
                    '0' => $helper->__('Disabled'),
                ),
            ),
            'content' => array(
                'type'         => 'editor',
                'required'     => true,
                'in_grid'      => false,
                'form_label'   => $helper->__('Content'),
                'form_wysiwyg' => true,
                'form_style'   => 'height:36em;',
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
        return array('block_id');
    }
    
    protected function _loadEditedEntity($blockType, BL_CustomGrid_Object $config, array $params, $entityId)
    {
        return Mage::getModel('cms/block')->load($entityId);
    }
    
    protected function _getLoadedEntityName($blockType, BL_CustomGrid_Object $config, array $params, $entity)
    {
        return $entity->getTitle();
    }
    
    protected function _getEditRequiredAclPermissions($blockType)
    {
        return 'cms/block';
    }
    
    protected function _applyEditedFieldValue($blockType, BL_CustomGrid_Object $config, array $params, $entity, $value)
    {
        if ($config->getValueId() == 'store_id') {
            $entity->setStores($value);
            return $this;
        }
        $entity->setStores($entity->getStoreId());
        return parent::_applyEditedFieldValue($blockType, $config, $params, $entity, $value);
    }
    
    protected function _getSavedFieldValueForRender($blockType, BL_CustomGrid_Object $config, array $params, $entity)
    {
        return ($config->getValueId() == 'store_id')
            ? $entity->getStores()
            : parent::_getSavedFieldValueForRender($blockType, $config, $params, $entity);
    }
}

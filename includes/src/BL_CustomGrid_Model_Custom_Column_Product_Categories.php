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

class BL_CustomGrid_Model_Custom_Column_Product_Categories extends BL_CustomGrid_Model_Custom_Column_Simple_Abstract
{
    const FILTER_MODE_CUSTOM       = 'custom';
    const FILTER_MODE_ONE_CHOOSEN  = 'one_choosen';
    const FILTER_MODE_ALL_CHOOSEN  = 'all_choosen';
    const FILTER_MODE_NONE_CHOOSEN = 'none_choosen';
    
    protected function _prepareConfig()
    {
        $helper = $this->_getBaseHelper();
        
        $descriptions = array(
            'separator' => 'Indicate here the string that will be used to separate category paths. '
                . 'If none is set, "<strong>, </strong>" will be used',
            'level_separator' => 'Indicate here the string that will be used to separate categories in each full path. '
                . 'If none is set, "<strong> > </strong>" will be used',
            'ascent_limit' => 'If needed, indicate here the level on which to stop ascent for the full paths display. '
                . 'At least one level will be displayed in all cases, even if the concerned category has a lower '
                . 'level',
            'custom_filter_operator' => 'Filtered products will have to belong to a number of categories amongst the '
                . 'chosen ones, that can be verified using this value and the one from the '
                . '"<strong>Custom Filter - Number</strong>" field',
            'custom_filter_number' => 'Filtered products will have to belong to a number of categories amongst the '
                . 'chosen ones, that can be verified using this value and the one from the '
                . '"<strong>Custom Filter - Operator</strong>" field',
            'display_ids' => 'Choose "<strong>Yes</strong>" to display IDs instead of names',
        );
        
        $this->addCustomizationParam(
            'boolean_filter',
            array(
                'label'        => $helper->__('With/Without Filter'),
                'group'        => $helper->__('Filtering'),
                'type'         => 'select',
                'source_model' => 'adminhtml/system_config_source_yesno',
                'value'        => 0,
            ),
            10
        );
        
        $this->addCustomizationParam(
            'filter_mode',
            array(
                'label'        => $helper->__('Filter Mode'),
                'group'        => $helper->__('Filtering'),
                'type'         => 'select',
                'source_model' => 'customgrid/system_config_source_product_categories_filter_mode',
                'value'        => self::FILTER_MODE_ONE_CHOOSEN,
                'depends'      => array('boolean_filter' => array('value' => 0)),
            ),
            20
        );
        
        $this->addCustomizationParam(
            'custom_filter_operator',
            array(
                'label'       => $helper->__('Custom Filter - Operator'),
                'group'       => $helper->__('Filtering'),
                'description' => $helper->__($descriptions['custom_filter_operator']),
                'type'        => 'select',
                'values'      => $this->getCustomFilterOperators(true),
                'depends'     => array(
                    'boolean_filter' => array('value' => 0),
                    'filter_mode'    => array('value' => self::FILTER_MODE_CUSTOM),
                ),
            ),
            30
        );
        
        $this->addCustomizationParam(
            'custom_filter_number',
            array(
                'label'       => $helper->__('Custom Filter - Number'),
                'group'       => $helper->__('Filtering'),
                'description' => $helper->__($descriptions['custom_filter_number']),
                'type'        => 'text',
                'depends'     => array(
                    'boolean_filter' => array('value' => 0),
                    'filter_mode'    => array('value' => self::FILTER_MODE_CUSTOM),
                ),
            ),
            40
        );
        
        $this->addCustomizationParam(
            'separator',
            array(
                'label'       => $helper->__('Categories Separator'),
                'group'       => $helper->__('Rendering'),
                'description' => $helper->__($descriptions['separator']),
                'type'        => 'text',
                'value'       => '',
            ),
            50
        );
        
        $this->addCustomizationParam(
            'full_paths',
            array(
                'label'        => $helper->__('Display Full Paths'),
                'group'        => $helper->__('Rendering'),
                'type'         => 'select',
                'source_model' => 'adminhtml/system_config_source_yesno',
                'value'        => 0,
            ),
            60
        );
        
        $this->addCustomizationParam(
            'level_separator',
            array(
                'label'       => $helper->__('Levels Separator'),
                'group'       => $helper->__('Rendering'),
                'description' => $helper->__($descriptions['level_separator']),
                'type'        => 'text',
                'value'       => '',
                'depends'     => array('full_paths' => array('value' => 1)),
            ),
            70
        );
        
        $this->addCustomizationParam(
            'ascent_limit',
            array(
                'label'       => $helper->__('Ascent Limit Level'),
                'group'       => $helper->__('Rendering'),
                'description' => $helper->__($descriptions['ascent_limit']),
                'type'        => 'text',
                'value'       => '',
                'depends'     => array('full_paths' => array('value' => 1)),
            ),
            80
        );
        
        $this->addCustomizationParam(
            'display_ids',
            array(
                'label'        => $helper->__('Display IDs'),
                'group'        => $helper->__('Rendering'),
                'description'  => $helper->__($descriptions['display_ids']),
                'type'         => 'select',
                'source_model' => 'adminhtml/system_config_source_yesno',
                'value'        => 0,
            ),
            90
        );
        
        $this->setCustomizationWindowConfig(array('height' => 520), true);
        return parent::_prepareConfig();
    }
    
    public function shouldInvalidateFilters(
        BL_CustomGrid_Model_Grid $gridModel,
        BL_CustomGrid_Model_Grid_Column $columnModel,
        array $params,
        array $renderers
    ) {
        if (!parent::shouldInvalidateFilters($gridModel, $columnModel, $params, $renderers)) {
            return ($this->_extractBoolParam($params['previous'], 'boolean_filter')
                XOR $this->_extractBoolParam($params['current'], 'boolean_filter'));
        }
        return true;
    }
    
    protected function _getCategoryIdsSelect(
        Varien_Data_Collection_Db $collection,
        $forFilter = false,
        $categoryIds = null
    ) {
        $mainAlias = $this->_getCollectionMainTableAlias($collection);
        list($adapter, $qi) = $this->_getCollectionAdapter($collection, true);
        $productAlias = $this->_getUniqueTableAlias($forFilter ? '_filter' : '_select');
        $mainField = ($forFilter ? 'COUNT(*)' : 'GROUP_CONCAT(' . $qi($productAlias . '.category_id') . ')');
        
        $select = $adapter->select()
            ->from(
                array($productAlias => $collection->getTable('catalog/category_product')),
                array('value' => new Zend_Db_Expr($mainField))
            )
            ->where($qi($productAlias . '.product_id') . ' = ' . $qi($mainAlias . '.entity_id'));
        
        if (!$forFilter) {
            $select->group($productAlias . '.product_id');
        }
        if (is_array($categoryIds)) {
            $select->where($qi($productAlias . '.category_id') . ' IN (?)', $categoryIds);
        }
        
        return $select;
    }
    
    public function addFieldToGridCollection(
        $columnIndex,
        array $params,
        Mage_Adminhtml_Block_Widget_Grid $gridBlock,
        Varien_Data_Collection_Db $collection
    ) {
        $idsQuery = '(' . $this->_getCategoryIdsSelect($collection) . ')';
        $collection->getSelect()->columns(array($columnIndex => new Zend_Db_Expr($idsQuery)));
        return $this;
    }
    
    public function addFilterToGridCollection(
        Varien_Data_Collection_Db $collection,
        Mage_Adminhtml_Block_Widget_Grid_Column $columnBlock
    ) {
        $params = $columnBlock->getBlcgFilterParams();
        
        if (is_array($params)) {
            if ($this->_extractBoolParam($params, 'boolean_filter')) {
                if (!is_null($filter = $columnBlock->getFilter()->getValue())) {
                    $countQuery = '(' . $this->_getCategoryIdsSelect($collection, true) . ')';
                    $collection->getSelect()->where(new Zend_Db_Expr($countQuery) . ' ' . ($filter ? '>' : '=') . ' 0');
                }
            } else {
                $categoryIds = array_filter(array_unique(explode(',', $columnBlock->getFilter()->getValue())));
                $filterMode  = $this->_extractStringParam($params, 'filter_mode', self::FILTER_MODE_ONE_CHOOSEN, true);
                $operator = '>=';
                $number = '1';
                
                if ($filterMode == self::FILTER_MODE_ALL_CHOOSEN) {
                    $number = count($categoryIds);
                } elseif ($filterMode == self::FILTER_MODE_NONE_CHOOSEN) {
                    $operator = '=';
                    $number = '0';
                } elseif ($filterMode == self::FILTER_MODE_CUSTOM) {
                    if (!is_int($number = $this->_extractIntParam($params, 'custom_filter_number', null, true))
                        || !($operator = $this->_extractStringParam($params, 'custom_filter_operator', null, true))) {
                        return $this;
                    }
                }
                
                $countQuery = '(' . $this->_getCategoryIdsSelect($collection, true, $categoryIds) . ')';
                $collection->getSelect()->where(new Zend_Db_Expr($countQuery) . ' ' . $operator . ' ' . $number);
            }
        }
        
        return $this;
    }
    
    public function getForcedBlockValues(
        Mage_Adminhtml_Block_Widget_Grid $gridBlock,
        BL_CustomGrid_Model_Grid $gridModel,
        $columnBlockId, $columnIndex,
        array $params,
        Mage_Core_Model_Store $store
    ) {
        $categoryTree = null;
        $categoryHash = array();
        $displayIds = $this->_extractBoolParam($params, 'display_ids');
        $displayFullPaths = $this->_extractBoolParam($params, 'full_paths');
        
        if ($displayFullPaths) {
            $categoryTree = Mage::getModel('catalog/category')
                ->getTreeModel()
                ->setStoreId($store->getId())
                ->load();
        }
        if (!$displayIds) {
            $collection = Mage::getModel('catalog/category')
                ->getCollection()
                ->setStoreId($store->getId())
                ->addAttributeToSelect('name')
                ->load(); 
            
            if (!is_null($categoryTree)) {
                $categoryTree->addCollectionData($collection);
            } else {
                foreach ($collection as $category) {
                    $categoryHash[$category->getId()] = $category;
                }
            }
        }
        
        return array(
            'filter'   => 'customgrid/widget_grid_column_filter_product_categories',
            'renderer' => 'customgrid/widget_grid_column_renderer_product_categories',
            'category_tree'      => $categoryTree,
            'category_hash'      => $categoryHash,
            'result_separator'   => $this->_extractStringParam($params, 'separator', ', ', true),
            'level_separator'    => $this->_extractStringParam($params, 'level_separator', ' > ', true),
            'ascent_limit'       => $this->_extractIntParam($params, 'ascent_limit', -1),
            'boolean_filter'     => $this->_extractBoolParam($params, 'boolean_filter'),
            'display_ids'        => $displayIds,
            'display_full_paths' => $displayFullPaths,
            'blcg_filter_params' => $params,
            'filter_condition_callback' => array($this, 'addFilterToGridCollection'),
        );
    }
    
    public function getCustomFilterOperators($asOptionArray = false)
    {
        $helper = $this->_getBaseHelper();
        
        if (!$this->hasData('custom_filter_operators')) {
            $this->setData(
                'custom_filter_operators',
                array(
                    '>'  => $helper->__('Greater than'),
                    '>=' => $helper->__('Greater than or equal to'),
                    '='  => $helper->__('Equal'),
                    '!=' => $helper->__('Not equal'),
                    '<=' => $helper->__('Lesser than or equal to'),
                    '<'  => $helper->__('Lesser than'),
                )
            );
        }
        
        $operators = $this->_getData('custom_filter_operators');
        return ($asOptionArray ? $helper->getOptionArrayFromOptionHash($operators) : $operators);
    }
}

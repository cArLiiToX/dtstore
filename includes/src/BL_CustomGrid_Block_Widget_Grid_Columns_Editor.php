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

class BL_CustomGrid_Block_Widget_Grid_Columns_Editor extends Mage_Adminhtml_Block_Template
{
    protected function _construct()
    {
        parent::_construct();
        $this->setId($this->helper('core')->uniqHash('blcgEditor'));
        $this->setTemplate('bl/customgrid/widget/grid/columns/editor.phtml');
    }
    
    protected function _toHtml()
    {
        if (!$this->getIsNewGridModel()
            && ($gridModel = $this->getGridModel())
            && $gridModel->checkUserPermissions(BL_CustomGrid_Model_Grid::ACTION_EDIT_COLUMNS_VALUES)
            && ($gridBlock = $this->getGridBlock())
            && $this->helper('customgrid')->isRewritedGridBlock($gridBlock)) {
            return parent::_toHtml();
        }
        return '';
    }
    
    public function getJsObjectName()
    {
        return $this->getId();
    }
    
    public function getGridTableId()
    {
        return $this->getGridBlock()->getId() . '_table';
    }
    
    public function getRowsJsonConfig()
    {
        $config    = array();
        $gridBlock = $this->getGridBlock();
        $gridModel = $this->getGridModel();
        
        if ($gridBlock->getCollection()) {
            foreach ($gridBlock->getCollection() as $row) {
                $config[] = $gridModel->getCollectionRowIdentifiers($row);
                
                // Avoid taking non-consistent rows
                if ($multipleRows = $gridBlock->getMultipleRows($row)) {
                    $rowsCount = count($multipleRows);
                    
                    for ($rowIndex=0; $rowIndex<$rowsCount; $rowIndex++) {
                        $config[] = false;
                    }
                }
                if ($gridBlock->shouldRenderSubTotal($row)) {
                    $config[] = false;
                }
            }
        }
        
        return $this->helper('core')->jsonEncode($config);
    }
    
    protected function _getGridBlockSortedColumns()
    {
        $columns = $this->getGridBlock()->getColumns();
        
        foreach ($columns as $key => $column) {
            if ($column->getBlcgFilterOnly()) {
                unset($columns[$key]);
            }
        }
        
        return $columns;
    }
    
    protected function _getEditableCustomizedColumns()
    {
        $columns = $this->getGridModel()->getSortedColumns(true, false, true, true, true, true);
        
        foreach ($columns as $columnBlockId => $column) {
            if ($column->isOnlyFilterable()) {
                unset($columns[$columnBlockId]);
            } elseif (!$column->isEditable() || !$column->isEditAllowed()) {
                $columns[$columnBlockId] = false;
            }
        }
        
        return $columns;
    }
    
    protected function _getEditableDefaultColumns()
    {
        $blockColumns = $this->_getGridBlockSortedColumns();
        $modelColumns = $this->getGridModel()->getColumns(true);
        $columns = array();
        
        foreach (array_keys($blockColumns) as $columnBlockId) {
            if (isset($modelColumns[$columnBlockId]) && $modelColumns[$columnBlockId]->isGrid()) {
                if ($modelColumns[$columnBlockId]->isOnlyFilterable()) {
                    continue;
                }
                $columns[$columnBlockId] = $modelColumns[$columnBlockId];
            } else {
                $columns[$columnBlockId] = false;
            }
        }
        
        return $columns;
    }
    
    public function getEditableColumnsJsonConfig()
    {
        $stringHelper = $this->helper('customgrid/string');
        $gridModel = $this->getGridModel();
        $config = array();
        
        if ($gridModel->checkUserPermissions(BL_CustomGrid_Model_Grid::ACTION_USE_CUSTOMIZED_COLUMNS)) {
            $columns = $this->_getEditableCustomizedColumns();
        } else {
            $columns = $this->_getEditableDefaultColumns();
        }
        if ($gridModel->hasUserEditPermissions($this->getGridBlock())) {
            foreach ($columns as $column) {
                if ($column !== false) {
                    $config[] = $stringHelper->camelizeArrayKeys($column->getEditConfig()->getEditorBlockData(), false);
                } else {
                    $config[] = false;
                }
            }
        } else {
            $config = array_fill(0, count($columns), false);
        }
        
        return $this->helper('core')->jsonEncode($config);
    }
    
    public function getAdditionalParamsJson()
    {
        return $this->helper('core')
            ->jsonEncode($this->getGridModel()->getAdditionalEditParams($this->getGridBlock()));
    }
    
    public function getGlobalParamsJson()
    {
        return $this->helper('core')
            ->jsonEncode(
                array(
                    'grid_id' => $this->getGridModel()->getId(),
                    'profile_id' => $this->getGridModel()->getProfileId(),
                    'editor_js_object_name' => $this->getJsObjectName(),
                )
            );
    }
}

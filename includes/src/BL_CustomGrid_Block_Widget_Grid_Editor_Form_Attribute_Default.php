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

class BL_CustomGrid_Block_Widget_Grid_Editor_Form_Attribute_Default extends
    BL_CustomGrid_Block_Widget_Grid_Editor_Form_Abstract
{
    protected function _prepareForm()
    {
        $form = $this->_initializeForm();
        $form->setDataObject($this->getEditedEntity());
        
        $config    = $this->getEditConfig();
        $entity    = $this->getEditedEntity();
        $attribute = $this->getEditedAttribute();
        $required  = '';
        
        if ($attribute->getIsRequired()) {
            $required = ' (<span class="blcg-editor-required-marker">' . $this->__('Required') . '</span>)';
        }
        
        $fieldset = $form->addFieldset(
            'base_fieldset',
            array(
                'legend' => $this->__(
                    '%s : %s',
                    $this->getEditedEntityName(),
                    $attribute->getFrontendLabel() . $required
                ),
                'class'  => 'fieldset-wide blcg-editor-fieldset',
            )
        );
        
        $this->_setFieldset(array($attribute), $fieldset);
        $form->addValues($entity->getData());
        $form->setFieldNameSuffix($config->getValuesKey());
        $this->setForm($form);
        
        return $this;
    }
    
    protected function _beforeToHtml()
    {
        return (is_object($this->getEditedAttribute()) ? parent::_beforeToHtml() : $this->setCanDisplay(false));
    }
    
    public function getIsRequiredValueEdit()
    {
        return (($attribute = $this->getEditedAttribute()) ? $attribute->getIsRequired() : false);
    }
}

<?php

class Cubix_AddressLabel_Block_Adminhtml_System_Config_Form_Fieldset_Modules_AddressLabel extends Mage_Adminhtml_Block_System_Config_Form_Fieldset {


    protected function _getHeaderHtml($element)
    {
        $html = parent::_getHeaderHtml($element);
        $html = '<img src="' . $this->getSkinUrl('images/cubix_addresslabel.png') . '" alt="Cubix Address Label explanation" />' . $html;
        return $html;
    }
}
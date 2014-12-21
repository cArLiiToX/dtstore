<?php
/**
 * Banners_Nikolai extension
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 * 
 * @category       Banners
 * @package        Banners_Nikolai
 * @copyright      Copyright (c) 2014
 * @license        http://opensource.org/licenses/mit-license.php MIT License
 */
/**
 * Banner DT Store admin block
 *
 * @category    Banners
 * @package     Banners_Nikolai
 * @author      Ultimate Module Creator
 */
class Banners_Nikolai_Block_Adminhtml_Bannerdt extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    /**
     * constructor
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function __construct()
    {
        $this->_controller         = 'adminhtml_bannerdt';
        $this->_blockGroup         = 'banners_nikolai';
        parent::__construct();
        $this->_headerText         = Mage::helper('banners_nikolai')->__('Banner DT Store');
        $this->_updateButton('add', 'label', Mage::helper('banners_nikolai')->__('Add Banner DT Store'));

    }
}

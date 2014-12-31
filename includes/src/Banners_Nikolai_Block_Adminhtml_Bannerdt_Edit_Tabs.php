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
 * Banner DT Store admin edit tabs
 *
 * @category    Banners
 * @package     Banners_Nikolai
 * @author      Ultimate Module Creator
 */
class Banners_Nikolai_Block_Adminhtml_Bannerdt_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    /**
     * Initialize Tabs
     *
     * @access public
     * @author Ultimate Module Creator
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('bannerdt_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('banners_nikolai')->__('Banner DT Store'));
    }

    /**
     * before render html
     *
     * @access protected
     * @return Banners_Nikolai_Block_Adminhtml_Bannerdt_Edit_Tabs
     * @author Ultimate Module Creator
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'form_bannerdt',
            array(
                'label'   => Mage::helper('banners_nikolai')->__('Banner DT Store'),
                'title'   => Mage::helper('banners_nikolai')->__('Banner DT Store'),
                'content' => $this->getLayout()->createBlock(
                    'banners_nikolai/adminhtml_bannerdt_edit_tab_form'
                )
                ->toHtml(),
            )
        );
        return parent::_beforeToHtml();
    }

    /**
     * Retrieve banner dt store entity
     *
     * @access public
     * @return Banners_Nikolai_Model_Bannerdt
     * @author Ultimate Module Creator
     */
    public function getBannerdt()
    {
        return Mage::registry('current_bannerdt');
    }
}

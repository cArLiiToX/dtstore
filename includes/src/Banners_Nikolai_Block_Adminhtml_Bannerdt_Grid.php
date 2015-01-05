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
 * Banner DT Store admin grid block
 *
 * @category    Banners
 * @package     Banners_Nikolai
 * @author      Ultimate Module Creator
 */
class Banners_Nikolai_Block_Adminhtml_Bannerdt_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * constructor
     *
     * @access public
     * @author Ultimate Module Creator
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('bannerdtGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    /**
     * prepare collection
     *
     * @access protected
     * @return Banners_Nikolai_Block_Adminhtml_Bannerdt_Grid
     * @author Ultimate Module Creator
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('banners_nikolai/bannerdt')
            ->getCollection();
        
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * prepare grid collection
     *
     * @access protected
     * @return Banners_Nikolai_Block_Adminhtml_Bannerdt_Grid
     * @author Ultimate Module Creator
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'entity_id',
            array(
                'header' => Mage::helper('banners_nikolai')->__('Id'),
                'index'  => 'entity_id',
                'type'   => 'number'
            )
        );
        $this->addColumn(
            'nombre',
            array(
                'header'    => Mage::helper('banners_nikolai')->__('Nombre'),
                'align'     => 'left',
                'index'     => 'nombre',
            )
        );
        
        $this->addColumn(
            'status',
            array(
                'header'  => Mage::helper('banners_nikolai')->__('Status'),
                'index'   => 'status',
                'type'    => 'options',
                'options' => array(
                    '1' => Mage::helper('banners_nikolai')->__('Enabled'),
                    '0' => Mage::helper('banners_nikolai')->__('Disabled'),
                )
            )
        );
        $this->addColumn(
            'onclick',
            array(
                'header' => Mage::helper('banners_nikolai')->__('Onclick Function'),
                'index'  => 'onclick',
                'type'=> 'text',

            )
        );
        $this->addColumn(
            'ordenamiento',
            array(
                'header' => Mage::helper('banners_nikolai')->__('Ordenamiento'),
                'index'  => 'ordenamiento',
                'type'=> 'text',

            )
        );
        $this->addColumn(
            'enlace',
            array(
                'header' => Mage::helper('banners_nikolai')->__('Enlace'),
                'index'  => 'enlace',
                'type'=> 'text',

            )
        );
        $this->addColumn(
            'seccion',
            array(
                'header' => Mage::helper('banners_nikolai')->__('Seccion Banner'),
                'index'  => 'seccion',
                'type'  => 'options',
                'options' => Mage::helper('banners_nikolai')->convertOptions(
                    Mage::getModel('banners_nikolai/bannerdt_attribute_source_seccion')->getAllOptions(false)
                )

            )
        );
        $this->addColumn(
            'created_at',
            array(
                'header' => Mage::helper('banners_nikolai')->__('Created at'),
                'index'  => 'created_at',
                'width'  => '120px',
                'type'   => 'datetime',
            )
        );
        $this->addColumn(
            'updated_at',
            array(
                'header'    => Mage::helper('banners_nikolai')->__('Updated at'),
                'index'     => 'updated_at',
                'width'     => '120px',
                'type'      => 'datetime',
            )
        );
        $this->addColumn(
            'action',
            array(
                'header'  =>  Mage::helper('banners_nikolai')->__('Action'),
                'width'   => '100',
                'type'    => 'action',
                'getter'  => 'getId',
                'actions' => array(
                    array(
                        'caption' => Mage::helper('banners_nikolai')->__('Edit'),
                        'url'     => array('base'=> '*/*/edit'),
                        'field'   => 'id'
                    )
                ),
                'filter'    => false,
                'is_system' => true,
                'sortable'  => false,
            )
        );
        $this->addExportType('*/*/exportCsv', Mage::helper('banners_nikolai')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('banners_nikolai')->__('Excel'));
        $this->addExportType('*/*/exportXml', Mage::helper('banners_nikolai')->__('XML'));
        return parent::_prepareColumns();
    }

    /**
     * prepare mass action
     *
     * @access protected
     * @return Banners_Nikolai_Block_Adminhtml_Bannerdt_Grid
     * @author Ultimate Module Creator
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('bannerdt');
        $this->getMassactionBlock()->addItem(
            'delete',
            array(
                'label'=> Mage::helper('banners_nikolai')->__('Delete'),
                'url'  => $this->getUrl('*/*/massDelete'),
                'confirm'  => Mage::helper('banners_nikolai')->__('Are you sure?')
            )
        );
        $this->getMassactionBlock()->addItem(
            'status',
            array(
                'label'      => Mage::helper('banners_nikolai')->__('Change status'),
                'url'        => $this->getUrl('*/*/massStatus', array('_current'=>true)),
                'additional' => array(
                    'status' => array(
                        'name'   => 'status',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => Mage::helper('banners_nikolai')->__('Status'),
                        'values' => array(
                            '1' => Mage::helper('banners_nikolai')->__('Enabled'),
                            '0' => Mage::helper('banners_nikolai')->__('Disabled'),
                        )
                    )
                )
            )
        );
        $this->getMassactionBlock()->addItem(
            'seccion',
            array(
                'label'      => Mage::helper('banners_nikolai')->__('Change Seccion Banner'),
                'url'        => $this->getUrl('*/*/massSeccion', array('_current'=>true)),
                'additional' => array(
                    'flag_seccion' => array(
                        'name'   => 'flag_seccion',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => Mage::helper('banners_nikolai')->__('Seccion Banner'),
                        'values' => Mage::getModel('banners_nikolai/bannerdt_attribute_source_seccion')
                            ->getAllOptions(true),

                    )
                )
            )
        );
        return $this;
    }

    /**
     * get the row url
     *
     * @access public
     * @param Banners_Nikolai_Model_Bannerdt
     * @return string
     * @author Ultimate Module Creator
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

    /**
     * get the grid url
     *
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current'=>true));
    }

    /**
     * after collection load
     *
     * @access protected
     * @return Banners_Nikolai_Block_Adminhtml_Bannerdt_Grid
     * @author Ultimate Module Creator
     */
    protected function _afterLoadCollection()
    {
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }
}

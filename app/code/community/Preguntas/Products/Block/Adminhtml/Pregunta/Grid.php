<?php
/**
 * Preguntas_Products extension
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 * 
 * @category       Preguntas
 * @package        Preguntas_Products
 * @copyright      Copyright (c) 2014
 * @license        http://opensource.org/licenses/mit-license.php MIT License
 */
/**
 * Preguntas Product admin grid block
 *
 * @category    Preguntas
 * @package     Preguntas_Products
 * @author      Ultimate Module Creator
 */
class Preguntas_Products_Block_Adminhtml_Pregunta_Grid extends Mage_Adminhtml_Block_Widget_Grid
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
        $this->setId('preguntaGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    /**
     * prepare collection
     *
     * @access protected
     * @return Preguntas_Products_Block_Adminhtml_Pregunta_Grid
     * @author Ultimate Module Creator
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('preguntas_products/pregunta')
            ->getCollection();
        
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * prepare grid collection
     *
     * @access protected
     * @return Preguntas_Products_Block_Adminhtml_Pregunta_Grid
     * @author Ultimate Module Creator
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'entity_id',
            array(
                'header' => Mage::helper('preguntas_products')->__('Id'),
                'index'  => 'entity_id',
                'type'   => 'number'
            )
        );
        $this->addColumn(
            'name',
            array(
                'header'    => Mage::helper('preguntas_products')->__('Nombre'),
                'align'     => 'left',
                'index'     => 'name',
            )
        );
        
        $this->addColumn(
            'status',
            array(
                'header'  => Mage::helper('preguntas_products')->__('Status'),
                'index'   => 'status',
                'type'    => 'options',
                'options' => array(
                    '1' => Mage::helper('preguntas_products')->__('Enabled'),
                    '0' => Mage::helper('preguntas_products')->__('Disabled'),
                )
            )
        );
        $this->addColumn(
            'email',
            array(
                'header' => Mage::helper('preguntas_products')->__('Email'),
                'index'  => 'email',
                'type'=> 'text',

            )
        );
        $this->addColumn(
            'pregunta',
            array(
                'header' => Mage::helper('preguntas_products')->__('Pregunta'),
                'index'  => 'pregunta',
                'type'=> 'text',

            )
        );
        $this->addColumn(
            'respuesta',
            array(
                'header' => Mage::helper('preguntas_products')->__('Respuesta'),
                'index'  => 'respuesta',
                'type'=> 'text',

            )
        );
        $this->addColumn(
            'contestada',
            array(
                'header' => Mage::helper('preguntas_products')->__('Contestada'),
                'index'  => 'contestada',
                'type'    => 'options',
                    'options'    => array(
                    '1' => Mage::helper('preguntas_products')->__('Yes'),
                    '0' => Mage::helper('preguntas_products')->__('No'),
                )

            )
        );
        $this->addColumn(
            'created_at',
            array(
                'header' => Mage::helper('preguntas_products')->__('Created at'),
                'index'  => 'created_at',
                'width'  => '120px',
                'type'   => 'datetime',
            )
        );
        $this->addColumn(
            'updated_at',
            array(
                'header'    => Mage::helper('preguntas_products')->__('Updated at'),
                'index'     => 'updated_at',
                'width'     => '120px',
                'type'      => 'datetime',
            )
        );
        $this->addColumn(
            'action',
            array(
                'header'  =>  Mage::helper('preguntas_products')->__('Action'),
                'width'   => '100',
                'type'    => 'action',
                'getter'  => 'getId',
                'actions' => array(
                    array(
                        'caption' => Mage::helper('preguntas_products')->__('Edit'),
                        'url'     => array('base'=> '*/*/edit'),
                        'field'   => 'id'
                    )
                ),
                'filter'    => false,
                'is_system' => true,
                'sortable'  => false,
            )
        );
        $this->addExportType('*/*/exportCsv', Mage::helper('preguntas_products')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('preguntas_products')->__('Excel'));
        $this->addExportType('*/*/exportXml', Mage::helper('preguntas_products')->__('XML'));
        return parent::_prepareColumns();
    }

    /**
     * prepare mass action
     *
     * @access protected
     * @return Preguntas_Products_Block_Adminhtml_Pregunta_Grid
     * @author Ultimate Module Creator
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('pregunta');
        $this->getMassactionBlock()->addItem(
            'delete',
            array(
                'label'=> Mage::helper('preguntas_products')->__('Delete'),
                'url'  => $this->getUrl('*/*/massDelete'),
                'confirm'  => Mage::helper('preguntas_products')->__('Are you sure?')
            )
        );
        $this->getMassactionBlock()->addItem(
            'status',
            array(
                'label'      => Mage::helper('preguntas_products')->__('Change status'),
                'url'        => $this->getUrl('*/*/massStatus', array('_current'=>true)),
                'additional' => array(
                    'status' => array(
                        'name'   => 'status',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => Mage::helper('preguntas_products')->__('Status'),
                        'values' => array(
                            '1' => Mage::helper('preguntas_products')->__('Enabled'),
                            '0' => Mage::helper('preguntas_products')->__('Disabled'),
                        )
                    )
                )
            )
        );
        $this->getMassactionBlock()->addItem(
            'contestada',
            array(
                'label'      => Mage::helper('preguntas_products')->__('Change Contestada'),
                'url'        => $this->getUrl('*/*/massContestada', array('_current'=>true)),
                'additional' => array(
                    'flag_contestada' => array(
                        'name'   => 'flag_contestada',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => Mage::helper('preguntas_products')->__('Contestada'),
                        'values' => array(
                                '1' => Mage::helper('preguntas_products')->__('Yes'),
                                '0' => Mage::helper('preguntas_products')->__('No'),
                            )

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
     * @param Preguntas_Products_Model_Pregunta
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
     * @return Preguntas_Products_Block_Adminhtml_Pregunta_Grid
     * @author Ultimate Module Creator
     */
    protected function _afterLoadCollection()
    {
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }
}

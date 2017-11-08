<?php
/**
 * Pixlogix Flexibleblog
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * @category   Pixlogix
 * @package    Pixlogix_Flexibleblog
 * @copyright  Copyright (c) 2016 Pixlogix Flexibleblog (http://www.pixlogix.com/)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Adminhtml_Tag_Grid block
 *
 * @category   Pixlogix
 * @package    Pixlogix_Flexibleblog
 * @author     Pixlogix Team <support@pixlogix.com>
 */

class Pixlogix_Flexibleblog_Block_Adminhtml_Tag_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * Initialize Tag Grid block
     *
     * @return Pixlogix_Flexibleblog_Block_Adminhtml_Tag_Grid
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('tagGrid');
        // This is the primary key of the database
        $this->setDefaultSort('tag_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    /**
     * _prepareCollection for tag table
     *
     * @return Pixlogix_Flexibleblog_Block_Adminhtml_Tag_Grid
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('flexibleblog/tag')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * _prepareColumns for tag table
     *
     * @return Pixlogix_Flexibleblog_Block_Adminhtml_Tag_Grid
     */
    protected function _prepareColumns()
    {
        $this->addColumn('tag_id', array(
            'header'    => Mage::helper('flexibleblog')->__('ID'),
            'align'     => 'right',
            'width'     => '50px',
            'index'     => 'tag_id',
        ));

        $this->addColumn('tag_name', array(
            'header'    => Mage::helper('flexibleblog')->__('Title'),
            'align'     => 'left',
            'index'     => 'tag_name',
        ));

        $this->addColumn('tag_url_key', array(
            'header'    => Mage::helper('flexibleblog')->__('Tag Url Key'),
            'align'     => 'left',
            'index'     => 'tag_url_key',
        ));

        $this->addColumn('action',
            array(
                'header'    => Mage::helper('flexibleblog')->__('Action'),
                'width'     => '100',
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => array(
                    array(
                        'caption'   => Mage::helper('flexibleblog')->__('Edit'),
                        'url'       => array('base'=> '*/*/edit'),
                        'field'     => 'id'
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
                'options' => array( array('label' => 'Delete', 'value' => 'delete') ) 
            )
        );

        $this->addExportType('*/*/exportCsv', Mage::helper('flexibleblog')->__('CSV'));
        $this->addExportType('*/*/exportXml', Mage::helper('flexibleblog')->__('XML'));
        $this->addExportType('*/*/exportExcel', Mage::helper('flexibleblog')->__('Excel'));

        return parent::_prepareColumns();
    }

    // For MassAction into Menu Grid
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('tag_id');
        $this->getMassactionBlock()->setFormFieldName('flexibleblog');

        $this->getMassactionBlock()->addItem('delete', array(
            'label'    => Mage::helper('flexibleblog')->__('Delete'),
            'url'      => $this->getUrl('*/*/massDelete'),
            'confirm'  => Mage::helper('flexibleblog')->__('Are you sure?')
        ));

        return $this;
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current'=>true));
    }
}
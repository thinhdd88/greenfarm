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
 * Adminhtml_Comment_Grid block
 *
 * @category   Pixlogix
 * @package    Pixlogix_Flexibleblog
 * @author     Pixlogix Team <support@pixlogix.com>
 */

class Pixlogix_Flexibleblog_Block_Adminhtml_Comment_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * Initialize Comment Grid block
     *
     * @return Pixlogix_Flexibleblog_Block_Adminhtml_Comment_Grid
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('commentGrid');
        // This is the primary key of the database
        $this->setDefaultSort('comment_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    /**
     * _prepareCollection for comment table
     *
     * @return Pixlogix_Flexibleblog_Block_Adminhtml_Comment_Grid
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('flexibleblog/comment')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * _prepareColumns for comment table
     *
     * @return Pixlogix_Flexibleblog_Block_Adminhtml_Comment_Grid
     */
    protected function _prepareColumns()
    {
        $this->addColumn('comment_id', array(
            'header'    => Mage::helper('flexibleblog')->__('ID'),
            'align'     => 'right',
            'width'     => '50px',
            'index'     => 'comment_id',
        ));

        $this->addColumn('post_id', array(
            'header'    => Mage::helper('flexibleblog')->__('Post'),
            'align'     => 'left',
            'width'     => '120px',
            'index'     => 'post_id',
            'renderer'  => 'Pixlogix_Flexibleblog_Block_Adminhtml_Comment_Renderer_Post',
            'filter_condition_callback' => array($this, '_postFilter'),
        ));

        $this->addColumn('comment_name', array(
            'header'    => Mage::helper('flexibleblog')->__('Name'),
            'align'     => 'left',
            'width'     => '120px',
            'index'     => 'comment_name',
        ));

        $this->addColumn('comment_email', array(
            'header'    => Mage::helper('flexibleblog')->__('Email'),
            'align'     => 'left',
            'index'     => 'comment_email',
            'renderer'  => 'Pixlogix_Flexibleblog_Block_Adminhtml_Comment_Renderer_Email'
        ));

        $this->addColumn('comment_website', array(
            'header'    => Mage::helper('flexibleblog')->__('Website'),
            'align'     => 'left',
            'index'     => 'comment_website',
            'renderer'  => 'Pixlogix_Flexibleblog_Block_Adminhtml_Comment_Renderer_Website'
        ));

        $this->addColumn('comment_description', array(
            'header'    => Mage::helper('flexibleblog')->__('Comment'),
            'align'     => 'left',
            'index'     => 'comment_description',
        ));

        $this->addColumn('comment_ip', array(
            'header'    => Mage::helper('flexibleblog')->__('Comment IP'),
            'align'     => 'left',
            'width'     => '160px',
            'index'     => 'comment_ip',
        ));

        $this->addColumn('comment_created_time', array(
            'header'    => Mage::helper('flexibleblog')->__('Creation Time'),
            'align'     => 'left',
            'width'     => '160px',
            'type'      => 'datetime',
            'default'   => '--',
            'index'     => 'comment_created_time',
        ));

        $this->addColumn('comment_update_time', array(
            'header'    => Mage::helper('flexibleblog')->__('Update Time'),
            'align'     => 'left',
            'width'     => '160px',
            'type'      => 'datetime',
            'default'   => '--',
            'index'     => 'comment_update_time',
        ));

        // Status options from Status Model class
        $statuses = Mage::getSingleton('flexibleblog/status')->getOptionArray();
        $this->addColumn('comment_status', array(
            'header'    => Mage::helper('flexibleblog')->__('Status'),
            'align'     => 'left',
            'width'     => '80px',
            'index'     => 'comment_status',
            'type'      => 'options',
            'options'   => $statuses,
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
                'is_system' => true,
            )
        );

        $this->addExportType('*/*/exportCsv', Mage::helper('flexibleblog')->__('CSV'));
        $this->addExportType('*/*/exportXml', Mage::helper('flexibleblog')->__('XML'));
        $this->addExportType('*/*/exportExcel', Mage::helper('flexibleblog')->__('Excel'));

        return parent::_prepareColumns();
    }

    // To filter Post Name by post Id on Comments Grid
    protected function _postFilter($collection, $column)
    {
	if (!$value = $column->getFilter()->getValue())
        {
            return $this;
        }
	$postIds = Mage::getModel('flexibleblog/comment')->filterPostNameColumn($value);
	$this->getCollection()->addFieldToFilter('post_id', array('in' => $postIds));
    }

    // For MassAction into Menu Grid
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('comment_id');
        $this->getMassactionBlock()->setFormFieldName('flexibleblog');

        $this->getMassactionBlock()->addItem('delete', array(
            'label'    => Mage::helper('flexibleblog')->__('Delete'),
            'url'      => $this->getUrl('*/*/massDelete'),
            'confirm'  => Mage::helper('flexibleblog')->__('Are you sure?')
        ));

        // Menu Status options from Status Model class
        $statuses = Mage::getSingleton('flexibleblog/status')->getOptionArray();
        array_unshift($statuses, array('label'=>'', 'value'=>''));
        $this->getMassactionBlock()->addItem('comment_status', array(
            'label'         => Mage::helper('flexibleblog')->__('Change status'),
            'url'           => $this->getUrl('*/*/massStatus', array('_current'=>true)),
            'additional'    => array(
                'visibility'    => array(
                    'name'      => 'comment_status',
                    'type'	=> 'select',
                    'class'	=> 'required-entry',
                    'label'	=> Mage::helper('flexibleblog')->__('Status'),
                    'values'    => $statuses
                )
            )
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
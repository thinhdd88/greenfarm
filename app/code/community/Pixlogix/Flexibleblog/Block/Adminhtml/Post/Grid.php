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
 * Adminhtml_Post_Grid block
 *
 * @category   Pixlogix
 * @package    Pixlogix_Flexibleblog
 * @author     Pixlogix Team <support@pixlogix.com>
 */

class Pixlogix_Flexibleblog_Block_Adminhtml_Post_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * Initialize Post Grid block
     *
     * @return Pixlogix_Flexibleblog_Block_Adminhtml_Post_Grid
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('postGrid');
        // This is the primary key of the database
        $this->setDefaultSort('post_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    /**
     * To filter Author Name on Author Grid
     *
     * @return Pixlogix_Flexibleblog_Block_Adminhtml_Post_Grid
     */
    protected function filterAuthorName($collection, $column)
    {
	if (!$value = $column->getFilter()->getValue())
        {
            return $this;
        }

        $authorModel = Mage::getModel('flexibleblog/author')->getCollection()->addFieldToFilter('author_name', array('like' => '%'.$value.'%'));
        foreach($authorModel as $authorId)
        {
                $arrAuthorId[]=$authorId->getAuthorId();
        }

        $authorIds = Mage::getModel('flexibleblog/author')->getCollection()->addFieldToFilter('author_name',array('eq','1'));
        foreach($authorIds as $authorId)
        {
                $arrAuthorId[]=$authorId->getAuthorId();
        }
        $this->getCollection()->addFieldToFilter('post_author', array('in' => $arrAuthorId));
    }

    /**
     * _prepareCollection for post table
     *
     * @return Pixlogix_Flexibleblog_Block_Adminhtml_Post_Grid
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('flexibleblog/post')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * _prepareColumns for post table
     *
     * @return Pixlogix_Flexibleblog_Block_Adminhtml_Post_Grid
     */
    protected function _prepareColumns()
    {
        $this->addColumn('post_id', array(
            'header'    => Mage::helper('flexibleblog')->__('ID'),
            'align'     => 'right',
            'width'     => '50px',
            'index'     => 'post_id',
        ));

        $this->addColumn('post_image', array(
            'header'    => $this->__('Image'),
            'align'     => 'center',
            'valign'     => 'center',
            'index'     => 'post_image',
            'width'     => '50px',
            'filter'    => false,
            'sortable'  => false,
            'renderer'  => 'Pixlogix_Flexibleblog_Block_Adminhtml_Post_Renderer_ImageGrid'
        ));

        $this->addColumn('post_title', array(
            'header'    => Mage::helper('flexibleblog')->__('Title'),
            'align'     => 'left',
            'index'     => 'post_title',
        ));

        $this->addColumn('post_url_key', array(
            'header'    => Mage::helper('flexibleblog')->__('Post Url Key'),
            'align'     => 'left',
            'index'     => 'post_url_key',
        ));

        $this->addColumn('post_author', array(
            'header'    => Mage::helper('flexibleblog')->__('Author'),
            'align'     => 'left',
            'width'     => '160px',
            'index'     => 'post_author',
			'filter_condition_callback' => array($this, 'filterAuthorName'),
			'renderer'  => 'Pixlogix_Flexibleblog_Block_Adminhtml_Post_Renderer_Authorname'
        ));

        // Comments options from Status Model class
        $statuses = Mage::getSingleton('flexibleblog/status')->getOptionArray();
        $this->addColumn('post_allow_comments', array(
            'header'    => Mage::helper('flexibleblog')->__('Comments'),
            'align'     => 'left',
            'width'     => '80px',
            'index'     => 'post_allow_comments',
            'type'      => 'options',
            'options'   => $statuses,
        ));

        $this->addColumn('post_created_time', array(
            'header'    => Mage::helper('flexibleblog')->__('Creation Time'),
            'align'     => 'left',
            'width'     => '160px',
            'type'      => 'datetime',
            'default'   => '--',
            'index'     => 'post_created_time',
        ));

        $this->addColumn('post_update_time', array(
            'header'    => Mage::helper('flexibleblog')->__('Update Time'),
            'align'     => 'left',
            'width'     => '160px',
            'type'      => 'datetime',
            'default'   => '--',
            'index'     => 'post_update_time',
        ));

        // Status options from Status Model class
        $statuses = Mage::getSingleton('flexibleblog/status')->getOptionArray();
        $this->addColumn('post_status', array(
            'header'    => Mage::helper('flexibleblog')->__('Status'),
            'align'     => 'left',
            'width'     => '80px',
            'index'     => 'post_status',
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

    // For MassAction into Menu Grid
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('post_id');
        $this->getMassactionBlock()->setFormFieldName('flexibleblog');

        $this->getMassactionBlock()->addItem('delete', array(
            'label'    => Mage::helper('flexibleblog')->__('Delete'),
            'url'      => $this->getUrl('*/*/massDelete'),
            'confirm'  => Mage::helper('flexibleblog')->__('Are you sure?')
        ));

        // Menu Status options from Status Model class
        $statuses = Mage::getSingleton('flexibleblog/status')->getOptionArray();
        array_unshift($statuses, array('label'=>'', 'value'=>''));
        $this->getMassactionBlock()->addItem('post_status', array(
            'label'         => Mage::helper('flexibleblog')->__('Change status'),
            'url'           => $this->getUrl('*/*/massStatus', array('_current'=>true)),
            'additional'    => array(
                'visibility'    => array(
                    'name'      => 'post_status',
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
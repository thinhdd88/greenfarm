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
 * Adminhtml_Author_Grid block
 *
 * @category   Pixlogix
 * @package    Pixlogix_Flexibleblog
 * @author     Pixlogix Team <support@pixlogix.com>
 */

class Pixlogix_Flexibleblog_Block_Adminhtml_Author_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * Initialize Author Grid block
     *
     * @return Pixlogix_Flexibleblog_Block_Adminhtml_Author_Grid
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('authorGrid');
        // This is the primary key of the database
        $this->setDefaultSort('author_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    /**
     * _prepareCollection for author table
     *
     * @return Pixlogix_Flexibleblog_Block_Adminhtml_Author_Grid
     */
    protected function _prepareCollection()
    {
        $prefix     = Mage::getConfig()->getTablePrefix();
        $collection = Mage::getModel('flexibleblog/author')->getCollection();
        $collection->getSelect()
            ->joinLeft(
                    array('p' => $prefix."flexibleblog_post"),
                    'p.post_author = main_table.author_id',
                    array('count(distinct post_author) as post_counter')
            )
            ->group(array('main_table.author_id'));
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * _prepareColumns for author table
     *
     * @return Pixlogix_Flexibleblog_Block_Adminhtml_Author_Grid
     */
    protected function _prepareColumns()
    {
        $this->addColumn('author_id', array(
            'header'    => Mage::helper('flexibleblog')->__('ID'),
            'align'     => 'right',
            'width'     => '50px',
            'index'     => 'author_id',
        ));

	$this->addColumn('author_avatar', array(
            'header'    => $this->__('Author Avatar'),
            'align'     => 'center',
            'valign'    => 'center',
            'index'     => 'author_avatar',
            'width'     => '50px',
            'filter'    => false,
            'sortable'  => false,
            'renderer'  => 'Pixlogix_Flexibleblog_Block_Adminhtml_Author_Renderer_ImageGrid'
        ));

        $this->addColumn('author_name', array(
            'header'    => Mage::helper('flexibleblog')->__('Author'),
            'align'     => 'left',
            'index'     => 'author_name',
        ));

        $this->addColumn('author_url_key', array(
            'header'    => Mage::helper('flexibleblog')->__('Author Url Key'),
            'align'     => 'left',
            'index'     => 'author_url_key',
        ));

        $this->addColumn('post_counter', array(
            'header'    => Mage::helper('flexibleblog')->__('No. of Post'),
            'align'     => 'left',
            'width'     => '50px',
            'sortable'  => false,
            'renderer'  => 'Pixlogix_Flexibleblog_Block_Adminhtml_Author_Renderer_PostCount',
            'filter_condition_callback' => array($this, '_postCounterFilter')
        ));

        $this->addColumn('author_created_time', array(
            'header'    => Mage::helper('flexibleblog')->__('Creation Time'),
            'align'     => 'left',
            'width'     => '160px',
            'type'      => 'datetime',
            'default'   => '--',
            'index'     => 'author_created_time',
        ));

        $this->addColumn('author_update_time', array(
            'header'    => Mage::helper('flexibleblog')->__('Update Time'),
            'align'     => 'left',
            'width'     => '160px',
            'type'      => 'datetime',
            'default'   => '--',
            'index'     => 'author_update_time',
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
        $this->setMassactionIdField('author_id');
        $this->getMassactionBlock()->setFormFieldName('flexibleblog');

        $this->getMassactionBlock()->addItem('delete', array(
            'label'    => Mage::helper('flexibleblog')->__('Delete'),
            'url'      => $this->getUrl('*/*/massDelete'),
            'confirm'  => Mage::helper('flexibleblog')->__('Are you sure?')
        ));

        return $this;
    }

    // Custom filter for Post counter
    public function _postCounterFilter($collection, $column)
    {
            if (!$value = (int)$column->getFilter()->getValue()) {
                $collection->getSelect()->having('count(distinct p.post_id)=0');
                return $this;
            }
            $collection->getSelect()->having('count(distinct p.post_id)='.$value);
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
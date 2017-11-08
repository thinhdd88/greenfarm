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
 * @package    Pixlogix_Flexibleblog0
 * @copyright  Copyright (c) 2016 Pixlogix Flexibleblog (http://www.pixlogix.com/)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Adminhtml_Category_Edit_Tabs block
 *
 * @category   Pixlogix
 * @package    Pixlogix_Flexibleblog
 * @author     Pixlogix Team <support@pixlogix.com>
 */

class Pixlogix_Flexibleblog_Block_Adminhtml_Category_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    /**
     * To initialize Tabs on Category Edit page
     *
     * @return Pixlogix_Flexibleblog_Block_Adminhtml_Category_Edit_Tabs
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('flexibleblog_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('flexibleblog')->__('Category Information'));
    }

    protected function _beforeToHtml()
    {
        $id         = $this->getRequest()->getParam('id');
        $this->addTab('category_form_section', array(
            'label'     => Mage::helper('flexibleblog')->__('Category Information'),
            'title'     => Mage::helper('flexibleblog')->__('Category Information'),
            'content'   => $this->getLayout()->createBlock('flexibleblog/adminhtml_category_edit_tab_form')->toHtml(),
            'url'       => $this->getUrl('*/*/edit', array('id' => $id, 'active_tab' => 'category_form_section')).'#',
            'class'     => 'pages-tabs'
        ));

        $this->addTab('category_content_section', array(
            'label'     => Mage::helper('flexibleblog')->__('Content'),
            'title'     => Mage::helper('flexibleblog')->__('Content'),
            'content'   => $this->getLayout()->createBlock('flexibleblog/adminhtml_category_edit_tab_content')->toHtml(),
            'url'       => $this->getUrl('*/*/edit', array('id' => $id, 'active_tab' => 'category_content_section')).'#',
            'class'     => 'pages-tabs'
        ));

        $this->addTab('category_design_section', array(
            'label'     => Mage::helper('flexibleblog')->__('Design'),
            'title'     => Mage::helper('flexibleblog')->__('Design'),
            'content'   => $this->getLayout()->createBlock('flexibleblog/adminhtml_category_edit_tab_design')->toHtml(),
            'url'       => $this->getUrl('*/*/edit', array('id' => $id, 'active_tab' => 'category_design_section')).'#',
            'class'     => 'pages-tabs'
        ));

        $this->addTab('category_meta_section', array(
            'label'     => Mage::helper('flexibleblog')->__('Meta Data'),
            'title'     => Mage::helper('flexibleblog')->__('Meta Data'),
            'content'   => $this->getLayout()->createBlock('flexibleblog/adminhtml_category_edit_tab_meta')->toHtml(),
            'url'       => $this->getUrl('*/*/edit', array('id' => $id, 'active_tab' => 'category_meta_section')).'#',
            'class'     => 'pages-tabs'
        ));
        return parent::_beforeToHtml();
    }
}
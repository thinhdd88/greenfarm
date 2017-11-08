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
 * Adminhtml_Post_Edit_Tabs block
 *
 * @category   Pixlogix
 * @package    Pixlogix_Flexibleblog
 * @author     Pixlogix Team <support@pixlogix.com>
 */

class Pixlogix_Flexibleblog_Block_Adminhtml_Post_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    /**
     * To initialize Posts on Post Edit page
     *
     * @return Pixlogix_Flexibleblog_Block_Adminhtml_Post_Edit_Tabs
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('flexibleblog_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('flexibleblog')->__('Post Information'));
    }

    protected function _beforeToHtml()
    {
        $id         = $this->getRequest()->getParam('id');
        $this->addTab('post_form_section', array(
            'label'     => Mage::helper('flexibleblog')->__('Post Information'),
            'title'     => Mage::helper('flexibleblog')->__('Post Information'),
            'content'   => $this->getLayout()->createBlock('flexibleblog/adminhtml_post_edit_tab_form')->toHtml(),
            'url'       => $this->getUrl('*/*/edit', array('id' => $id, 'active_tab' => 'post_form_section')).'#',
            'class'     => 'pages-tabs'
        ));

        $this->addTab('post_content_section', array(
            'label'     => Mage::helper('flexibleblog')->__('Content'),
            'title'     => Mage::helper('flexibleblog')->__('Content'),
            'content'   => $this->getLayout()->createBlock('flexibleblog/adminhtml_post_edit_tab_content')->toHtml(),
            'url'       => $this->getUrl('*/*/edit', array('id' => $id, 'active_tab' => 'post_content_section')).'#',
            'class'     => 'pages-tabs'
        ));

        $this->addTab('post_association_section', array(
            'label'     => Mage::helper('flexibleblog')->__('Association'),
            'title'     => Mage::helper('flexibleblog')->__('Association'),
            'content'   => $this->getLayout()->createBlock('flexibleblog/adminhtml_post_edit_tab_association')->toHtml(),
            'url'       => $this->getUrl('*/*/edit', array('id' => $id, 'active_tab' => 'post_association_section')).'#',
            'class'     => 'pages-tabs'
        ));

        $this->addTab('post_general_section', array(
            'label'     => Mage::helper('flexibleblog')->__('General'),
            'title'     => Mage::helper('flexibleblog')->__('General'),
            'content'   => $this->getLayout()->createBlock('flexibleblog/adminhtml_post_edit_tab_general')->toHtml(),
            'url'       => $this->getUrl('*/*/edit', array('id' => $id, 'active_tab' => 'post_general_section')).'#',
            'class'     => 'pages-tabs'
        ));

        if(!Mage::app()->isSingleStoreMode()){
            $this->addTab('post_website_section', array(
                'label'     => Mage::helper('flexibleblog')->__('Website'),
                'title'     => Mage::helper('flexibleblog')->__('Website'),
                'content'   => $this->getLayout()->createBlock('flexibleblog/adminhtml_post_edit_tab_website')->toHtml(),
		'url'       => $this->getUrl('*/*/edit', array('id' => $id, 'active_tab' => 'post_website_section')).'#',
                'class'     => 'pages-tabs'
            ));
        }

        $this->addTab('post_design_section', array(
            'label'     => Mage::helper('flexibleblog')->__('Design'),
            'title'     => Mage::helper('flexibleblog')->__('Design'),
            'content'   => $this->getLayout()->createBlock('flexibleblog/adminhtml_post_edit_tab_design')->toHtml(),
            'url'       => $this->getUrl('*/*/edit', array('id' => $id, 'active_tab' => 'post_design_section')).'#',
            'class'     => 'pages-tabs'
        ));

        $this->addTab('post_meta_section', array(
            'label'     => Mage::helper('flexibleblog')->__('Meta Data'),
            'title'     => Mage::helper('flexibleblog')->__('Meta Data'),
            'content'   => $this->getLayout()->createBlock('flexibleblog/adminhtml_post_edit_tab_meta')->toHtml(),
            'url'       => $this->getUrl('*/*/edit', array('id' => $id, 'active_tab' => 'post_meta_section')).'#',
            'class'     => 'pages-tabs'
        ));

        return parent::_beforeToHtml();
    }
}
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
 * Adminhtml_Author_Edit block
 *
 * @category   Pixlogix
 * @package    Pixlogix_Flexibleblog
 * @author     Pixlogix Team <support@pixlogix.com>
 */

class Pixlogix_Flexibleblog_Block_Adminhtml_Author_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    /**
     * _prepareLayout for WYSIWYG editor
     *
     * @return Pixlogix_Flexibleblog_Block_Adminhtml_Author_Edit
     */
    protected function _prepareLayout()
    {
        // To "wysiwyg" Editor on Form Edit/Add post page
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
        }
	parent::_prepareLayout();
    }

    /**
     * To initialize Author Edit page with Buttons
     *
     * @return Pixlogix_Flexibleblog_Block_Adminhtml_Author_Edit
     */
    public function __construct()
    {
        parent::__construct();

        $this->_objectId = 'id';
        $this->_blockGroup = 'flexibleblog';
        $this->_controller = 'adminhtml_author';

        $this->_updateButton('save', 'label', Mage::helper('flexibleblog')->__('Save Author'));
        $this->_updateButton('delete', 'label', Mage::helper('flexibleblog')->__('Delete Author'));

        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('web_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'web_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'web_content');
                }
            }
            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if( Mage::registry('flexibleblog_data') && Mage::registry('flexibleblog_data')->getId() ) {
            return Mage::helper('flexibleblog')->__("Edit Author '%s'", $this->htmlEscape(Mage::registry('flexibleblog_data')->getAuthorName()));
        } else {
            return Mage::helper('flexibleblog')->__('Add Author');
        }
    }
}
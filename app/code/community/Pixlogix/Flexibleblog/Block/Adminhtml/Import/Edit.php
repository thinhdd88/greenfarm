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
 * Adminhtml_Import_Edit block
 *
 * @category   Pixlogix
 * @package    Pixlogix_Flexibleblog
 * @author     Pixlogix Team <support@pixlogix.com>
 */

class Pixlogix_Flexibleblog_Block_Adminhtml_Import_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    /**
     * _prepareLayout for tab active js and WYSIWYG editor
     *
     * @return Pixlogix_Flexibleblog_Block_Adminhtml_Import_Edit
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
    }

    /**
     * To initialize Import Edit page with Buttons
     *
     * @return Pixlogix_Flexibleblog_Block_Adminhtml_Import_Edit
     */
    public function __construct()
    {
        parent::__construct();

        $this->_objectId = 'id';
        $this->_blockGroup = 'flexibleblog';
        $this->_controller = 'adminhtml_import';
        $this->_removeButton('save');
        $this->_addButton( 'saveandcontinue', array(
                                                'label'   => Mage::helper('adminhtml')->__('Import Data'),
                                                'onclick' => 'saveAndContinueEdit()',
                                                'class'   => 'save',
                                            ),
                                            -100
                            );

        $this->_formScripts[]
            = "function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            } ";
    }

    public function getHeaderText()
    {
        return Mage::helper('flexibleblog')->__('Import Data');
    }

    public function getImportUrl()
    {
        return $this->getUrl('*/*/import', array(
            '_current'	=> true,
        ));
    }

    public function getSaveAndContinueUrl()
    {
        return $this->getUrl('*/*/save', array(
            '_current'	=> true,
            'back'	=> 'edit',
        ));
    }
}
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
 * Adminhtml_Category_Edit_Tab_Form block
 *
 * @category   Pixlogix
 * @package    Pixlogix_Flexibleblog
 * @author     Pixlogix Team <support@pixlogix.com>
 */

class Pixlogix_Flexibleblog_Block_Adminhtml_Import_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * To prepare Category Information Tab on Category Edit page
     *
     * @return Pixlogix_Flexibleblog_Block_Adminhtml_Import_Edit_Tab_Form
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('import_form_fieldset', array(
            'legend'    => Mage::helper('flexibleblog')->__('Import Setting'),
        ));
        
        $fieldset->addField('import_file', 'file', array(
            'label'     => Mage::helper('flexibleblog')->__('Select File to Import'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'import_file',
            'note'      => 'You can import blog data by uploading .csv file. See sample.csv file before import data.<br/><a href="'.$this->getUrl('*/*/download').'" target="_blank">Download sample.csv</a>'
        ));

        return parent::_prepareForm();
    }
}
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

class Pixlogix_Flexibleblog_Block_Adminhtml_Category_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * To prepare Category Information Tab on Category Edit page
     *
     * @return Pixlogix_Flexibleblog_Block_Adminhtml_Category_Edit_Tab_Form
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('category_form_fieldset', array(
            'legend'    => Mage::helper('flexibleblog')->__('Category Information'),
        ));

        $fieldset->addField('category_title', 'text', array(
            'label'     => Mage::helper('flexibleblog')->__('Category Title'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'category_title',
        ));

        $fieldset->addField('category_url_key', 'text', array(
            'label'     => Mage::helper('flexibleblog')->__('Url Key'),
            //'class'     => 'required-entry',
            'required'  => false,
            'name'      => 'category_url_key',
        ));

        // Status options from Status Model class
        $statuses = Mage::getSingleton('flexibleblog/status')->getOptionArray();
        $fieldset->addField('category_status', 'select', array(
            'label'     => Mage::helper('flexibleblog')->__('Status'),
            'name'      => 'category_status',
            'required'  => true,
            'values'    => $statuses,
        ));

        if ( Mage::getSingleton('adminhtml/session')->getFlexibleblogData() )
        {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getFlexibleblogData());
            Mage::getSingleton('adminhtml/session')->setFlexibleblogData(null);
        }
        elseif ( Mage::registry('flexibleblog_data') )
        {
            $form->setValues(Mage::registry('flexibleblog_data')->getData());
        }
        return parent::_prepareForm();
    }
}
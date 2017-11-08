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
 * Adminhtml_Post_Edit_Tab_Website block
 *
 * @category   Pixlogix
 * @package    Pixlogix_Flexibleblog
 * @author     Pixlogix Team <support@pixlogix.com>
 */

class Pixlogix_Flexibleblog_Block_Adminhtml_Post_Edit_Tab_Website extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * To prepare Post Website Tab on Post Edit page
     *
     * @return Pixlogix_Flexibleblog_Block_Adminhtml_Post_Edit_Tab_Website
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('post_form_fieldset', array(
            'legend'    => Mage::helper('flexibleblog')->__('Website'),
        ));

        $isElementDisabled = false;
        if (!Mage::app()->isSingleStoreMode()) {
            $fieldset->addField('post_store_view', 'multiselect', array(
                'name'      => 'stores[]',
                'label'     => Mage::helper('flexibleblog')->__('Store View'),
                'title'     => Mage::helper('flexibleblog')->__('Store View'),
                'required'  => true,
                'values'    => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true),
                'disabled'  => $isElementDisabled,
                'note'      => 'Hold CTRL to select multiple'
            ));
        } else {
            $fieldset->addField('post_store_view', 'hidden', array(
                'name' => 'post_store_view[]',
                'value' => Mage::app()->getStore(true)->getId()
            ));
        }

        if( Mage::getSingleton('adminhtml/session')->getFlexibleblogData() )
        {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getFlexibleblogData());
            Mage::getSingleton('adminhtml/session')->setFlexibleblogData(null);
        } elseif ( Mage::registry('flexibleblog_data') ) {
            $form->setValues(Mage::registry('flexibleblog_data')->getData());
        }
        return parent::_prepareForm();
    }
}
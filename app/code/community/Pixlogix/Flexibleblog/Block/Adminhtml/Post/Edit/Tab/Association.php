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
 * Adminhtml_Post_Edit_Tab_Association block
 *
 * @category   Pixlogix
 * @package    Pixlogix_Flexibleblog
 * @author     Pixlogix Team <support@pixlogix.com>
 */

class Pixlogix_Flexibleblog_Block_Adminhtml_Post_Edit_Tab_Association extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * To prepare Post Association Tab on Post Edit page
     *
     * @return Pixlogix_Flexibleblog_Block_Adminhtml_Post_Edit_Tab_Association
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('post_association_fieldset', array(
            'legend'    => Mage::helper('flexibleblog')->__('Post Association')
        ));

        // Categories list from Status Model class
        $categories = Mage::getSingleton('flexibleblog/post')->getCategoryCollection();
        $fieldset->addField('post_categories', 'multiselect', array(
            'label'     => Mage::helper('flexibleblog')->__('Post Categories'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'post_categories',
            'values'    => $categories,
            'note'      => 'Hold CTRL to select multiple',
        ));

        $fieldset->addField('post_tags', 'text', array(
            'label'     => Mage::helper('flexibleblog')->__('Tags'),
            'required'  => false,
            'name'      => 'post_tags',
            'note'      => 'Add multiple Tags separated by comma'
        ));

        // Status options from Status Model class
        $statuses = Mage::getSingleton('flexibleblog/status')->getOptionArray();
        $fieldset->addField('post_allow_comments', 'select', array(
            'label'     => Mage::helper('flexibleblog')->__('Comments'),
            'required'  => false,
            'name'      => 'post_allow_comments',
            'values'    => $statuses,
        ));

        if( Mage::getSingleton('adminhtml/session')->getFlexibleblogData() )
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
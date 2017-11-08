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
 * Adminhtml_Category_Edit_Tab_Meta block
 *
 * @category   Pixlogix
 * @package    Pixlogix_Flexibleblog
 * @author     Pixlogix Team <support@pixlogix.com>
 */

class Pixlogix_Flexibleblog_Block_Adminhtml_Category_Edit_Tab_Meta extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * To prepare Category Meta Tab on Category Edit page
     *
     * @return Pixlogix_Flexibleblog_Block_Adminhtml_Category_Edit_Tab_Meta
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('post_meta_fieldset', array(
            'legend'    => Mage::helper('flexibleblog')->__('Meta Data'),
            'class'     => 'fieldset-wide',
        ));

        $fieldset->addField('category_meta_title', 'text', array(
            'name'      => 'category_meta_title',
            'label'     => Mage::helper('flexibleblog')->__('Meta Title'),
            'required'  => false,
        ));

        $fieldset->addField('category_meta_keyword', 'editor', array(
            'name'      => 'category_meta_keyword',
            'label'     => Mage::helper('flexibleblog')->__('Meta Keywords'),
            'title'     => Mage::helper('flexibleblog')->__('Meta Keywords'),
            'required'  => false,
        ));

        $fieldset->addField('category_meta_description', 'editor', array(
            'name'      => 'category_meta_description',
            'label'     => Mage::helper('flexibleblog')->__('Meta Description'),
            'title'     => Mage::helper('flexibleblog')->__('Meta Description'),
            'required'  => false,
            'note'      => Mage::helper('flexibleblog')->__('Maximum 255 chars'),
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
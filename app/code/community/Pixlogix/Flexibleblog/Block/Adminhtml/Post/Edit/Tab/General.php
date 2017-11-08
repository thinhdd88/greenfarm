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
 * Adminhtml_Post_Edit_Tab_General block
 *
 * @category   Pixlogix
 * @package    Pixlogix_Flexibleblog
 * @author     Pixlogix Team <support@pixlogix.com>
 */

class Pixlogix_Flexibleblog_Block_Adminhtml_Post_Edit_Tab_General extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * To prepare Post General Tab on Post Edit page
     *
     * @return Pixlogix_Flexibleblog_Block_Adminhtml_Post_Edit_Tab_General
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('post_general_fieldset', array(
            'legend'    => Mage::helper('flexibleblog')->__('General'),
        ));

        $fieldset->addType('image', 'Pixlogix_Flexibleblog_Block_Adminhtml_Post_Renderer_Image');
        $fieldset->addField('post_image', 'image', array(
            'name'      => 'post_image',
            'label'     => Mage::helper('flexibleblog')->__('Image'),
            'title'     => Mage::helper('flexibleblog')->__('Image'),
            'required'  => false,
        ));

        $dateFormatIso = Mage::app()->getLocale()->getDateTimeFormat(
            Mage_Core_Model_Locale::FORMAT_TYPE_MEDIUM
        );

        $fieldset->addField('post_publish_date', 'date', array(
            'name'      => 'post_publish_date',
            'label'     => Mage::helper('flexibleblog')->__('Publish Date'),
            'title'     => Mage::helper('flexibleblog')->__('Publish Date'),
            'image'     => $this->getSkinUrl('images/grid-cal.gif'),
            'input_format' => Varien_Date::DATETIME_INTERNAL_FORMAT,
            'format'    => $dateFormatIso,
            'time'      => true,
            'note'      => Mage::helper('flexibleblog')->__('Leave empty to use current datetime'),
            'class'     => 'validate-date',
            'required'  => false,
        ));

        // Author options from Author Model class
        $authors = Mage::getSingleton('flexibleblog/author')->getOptionArray();
        array_unshift($authors, array('label'=>'--Please Select--', 'value'=>''));
        $fieldset->addField('post_author', 'select', array(
            'name'      => 'post_author',
            'label'     => Mage::helper('flexibleblog')->__('Author'),
            'title'     => Mage::helper('flexibleblog')->__('Author'),
            'values'    => $authors,
            'required'  => true,
        ));

        if( Mage::getSingleton('adminhtml/session')->getFlexibleblogData() )
        {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getFlexibleblogData());
            Mage::getSingleton('adminhtml/session')->setFlexibleblogData(null);
        }
        elseif ( Mage::registry('flexibleblog_data') ) {
            $form->setValues(Mage::registry('flexibleblog_data')->getData());
        }
        return parent::_prepareForm();
    }
}
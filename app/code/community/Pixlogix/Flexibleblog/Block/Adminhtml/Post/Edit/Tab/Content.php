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
 * Adminhtml_Post_Edit_Tab_Content block
 *
 * @category   Pixlogix
 * @package    Pixlogix_Flexibleblog
 * @author     Pixlogix Team <support@pixlogix.com>
 */

class Pixlogix_Flexibleblog_Block_Adminhtml_Post_Edit_Tab_Content extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * To prepare Post Content Tab on Post Edit page
     *
     * @return Pixlogix_Flexibleblog_Block_Adminhtml_Post_Edit_Tab_Content
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);

        $fieldset = $form->addFieldset('post_content_fieldset', array(
            'legend'    => Mage::helper('flexibleblog')->__('Content'),
        ));

        // To get WYSIWYG Editor
        $wysiwygConfig = Mage::getSingleton('cms/wysiwyg_config')->getConfig(
            array('tab_id' => $this->getTabId())
        );

        $fieldset->addField('post_description', 'editor', array(
            'name'      => 'post_description',
            'label'     => Mage::helper('flexibleblog')->__('Description'),
            'title'     => Mage::helper('flexibleblog')->__('Description'),
            'style'     => 'height:20em; width:597px;',
            'config'    => $wysiwygConfig,
            'wysiwyg'   => true,
            'required'  => true,
        ));

        $fieldset->addField('post_short_description', 'editor', array(
            'name'      => 'post_short_description',
            'label'     => Mage::helper('flexibleblog')->__('Short Description'),
            'title'     => Mage::helper('flexibleblog')->__('Short Description'),
            'style'     => 'height:15em; width:597px;',
            'config'    => $wysiwygConfig,
            'wysiwyg'   => true,
            'required'  => false,
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
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
 * Adminhtml_Author_Edit_Tab_Form block
 *
 * @category   Pixlogix
 * @package    Pixlogix_Flexibleblog
 * @author     Pixlogix Team <support@pixlogix.com>
 */

class Pixlogix_Flexibleblog_Block_Adminhtml_Author_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * To prepare Author Information Tab on Author Edit page
     *
     * @return Pixlogix_Flexibleblog_Block_Adminhtml_Author_Edit_Tab_Form
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('author_form_fieldset', array(
            'legend'    => Mage::helper('flexibleblog')->__('Author Information'),
        ));

	// To get WYSIWYG Editor
        $wysiwygConfig = Mage::getSingleton('cms/wysiwyg_config')->getConfig(
            array('tab_id' => $this->getTabId())
        );

        $fieldset->addField('author_name', 'text', array(
            'label'     => Mage::helper('flexibleblog')->__('Author Name'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'author_name',
            'note'      => Mage::helper('flexibleblog')->__('i.e. John Doe'),
        ));

        $fieldset->addField('author_url_key', 'text', array(
            'label'     => Mage::helper('flexibleblog')->__('Url Key'),
            'required'  => false,
            'name'      => 'author_url_key',
        ));

	$fieldset->addType('image', 'Pixlogix_Flexibleblog_Block_Adminhtml_Author_Renderer_Image');
        $fieldset->addField('author_avatar', 'image', array(
            'name'      => 'author_avatar',
            'label'     => Mage::helper('flexibleblog')->__('Author Avatar'),
            'title'     => Mage::helper('flexibleblog')->__('Author Avatar'),
            'required'  => false,
        ));

	$fieldset->addField('author_bio', 'editor', array(
            'name'      => 'author_bio',
            'label'     => Mage::helper('flexibleblog')->__('Author Bio'),
            'title'     => Mage::helper('flexibleblog')->__('Author Bio'),
            'style'     => 'height:20em; width:597px;',
            'config'    => $wysiwygConfig,
            'wysiwyg'   => true,
        ));

        if ( Mage::getSingleton('adminhtml/session')->getFlexibleblogData() )
        {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getFlexibleblogData());
            Mage::getSingleton('adminhtml/session')->setFlexibleblogData(null);
        } elseif ( Mage::registry('flexibleblog_data') ) {
            $form->setValues(Mage::registry('flexibleblog_data')->getData());
        }
        return parent::_prepareForm();
    }
}
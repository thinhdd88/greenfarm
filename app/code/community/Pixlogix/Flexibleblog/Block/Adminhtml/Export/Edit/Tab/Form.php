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
 * Adminhtml_Export_Edit_Tab_Form block
 *
 * @category   Pixlogix
 * @package    Pixlogix_Flexibleblog
 * @author     Pixlogix Team <support@pixlogix.com>
 */

class Pixlogix_Flexibleblog_Block_Adminhtml_Export_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * To prepare Category Information Tab on Category Edit page
     *
     * @return Pixlogix_Flexibleblog_Block_Adminhtml_Export_Edit_Tab_Form
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm( $form );
        $fieldset = $form->addFieldset( 'export_form_fieldset', array(
            'legend'    => Mage::helper( 'flexibleblog' )->__( 'Export Setting' ),
        ));

        $dateFormatIso = Mage::app()->getLocale()->getDateTimeFormat(
            Mage_Core_Model_Locale::FORMAT_TYPE_MEDIUM
        );
        
        $fieldset->addField( 'export_date_from', 'date', array(
            'label'         => Mage::helper( 'flexibleblog' )->__( 'Date From' ),
            'name'          => 'export_date_from',
            'image'         => $this->getSkinUrl( 'images/grid-cal.gif' ),
            'input_format'  => Varien_Date::DATETIME_INTERNAL_FORMAT,
            'format'        => $dateFormatIso,
            'time'          => true,
            'note'          => Mage::helper( 'flexibleblog' )->__( 'Post publish date' ),
            'class'         => 'validate-date',
            'required'      => false,
        ));

        $fieldset->addField( 'export_date_to', 'date', array(
            'label'         => Mage::helper( 'flexibleblog' )->__( 'Date To' ),
            'name'          => 'export_date_to',
            'image'         => $this->getSkinUrl( 'images/grid-cal.gif' ),
            'input_format'  => Varien_Date::DATETIME_INTERNAL_FORMAT,
            'format'        => $dateFormatIso,
            'time'          => true,
            'note'          => Mage::helper( 'flexibleblog' )->__( 'Post publish date' ),
            'class'         => 'validate-date',
            'required'      => false,
        ));

        $isElementDisabled = false;
        if (!Mage::app()->isSingleStoreMode()) {
            $fieldset->addField('post_store_view', 'multiselect', array(
                'name'      => 'stores[]',
                'label'     => Mage::helper('flexibleblog')->__('Store View'),
                'title'     => Mage::helper('flexibleblog')->__('Store View'),
                'values'    => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true),
                'disabled'  => $isElementDisabled,
                'note'      => 'Hold CTRL to select multiple'
            ));
        }

        // Categories list from Status Model class
        $categories = Mage::getSingleton( 'flexibleblog/post' )->getCategoryCollection();
        $fieldset->addField( 'post_categories', 'multiselect', array(
            'label'     => Mage::helper( 'flexibleblog' )->__( 'Post Categories' ),
            'name'      => 'post_categories',
            'values'    => $categories,
            'note'      => 'Hold CTRL to select multiple',
        ));

        $fieldset->addField( 'post_tags', 'text', array(
            'label'     => Mage::helper( 'flexibleblog' )->__( 'Tags' ),
            'required'  => false,
            'name'      => 'post_tags',
            'note'      => 'Add multiple Tags separated by comma'
        ));

        // Author options from Author Model class
        $authors = Mage::getSingleton( 'flexibleblog/author' )->getOptionArray();
        array_unshift($authors, array( 'label'=>'All', 'value'=>'' ) );
        $fieldset->addField( 'post_author', 'select', array(
            'name'      => 'post_author',
            'label'     => Mage::helper( 'flexibleblog' )->__( 'Author' ),
            'title'     => Mage::helper( 'flexibleblog' )->__( 'Author' ),
            'values'    => $authors,
        ));
        
        // Status options from Status Model class
        $statuses = Mage::getSingleton( 'flexibleblog/status' )->getOptionArray();
        array_unshift($statuses, array( 'label'=>'All', 'value'=>'0' ) );
        $fieldset->addField( 'post_status', 'select', array(
            'label'     => Mage::helper( 'flexibleblog' )->__( 'Status' ),
            'name'      => 'post_status',
            'values'    => $statuses,
        ));
        return parent::_prepareForm();
    }
}
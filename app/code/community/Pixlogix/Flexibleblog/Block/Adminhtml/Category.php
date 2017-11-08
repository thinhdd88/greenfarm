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
 * Adminhtml_Category block
 *
 * @category   Pixlogix
 * @package    Pixlogix_Flexibleblog
 * @author     Pixlogix Team <support@pixlogix.com>
 */

class Pixlogix_Flexibleblog_Block_Adminhtml_Category extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    /**
     * Initialize Adminhtml Category block
     *
     * @return Pixlogix_Flexibleblog_Block_Adminhtml_Category
     */
    public function __construct()
    {
        $this->_blockGroup = 'flexibleblog';
        $this->_controller = 'adminhtml_category';
        $this->_headerText = Mage::helper('flexibleblog')->__('Manage Categories');
        $this->_addButtonLabel = Mage::helper('flexibleblog')->__('Add Category');
        parent::__construct();
    }
}
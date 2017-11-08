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
 * Adminhtml_Author_Renderer_PostCount block
 *
 * @category   Pixlogix
 * @package    Pixlogix_Flexibleblog
 * @author     Pixlogix Team <support@pixlogix.com>
 */

class Pixlogix_Flexibleblog_Block_Adminhtml_Author_Renderer_PostCount extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    /**
     * Renderer class to display Number of Posts Count on Author Grid
     *
     * @return Pixlogix_Flexibleblog_Block_Adminhtml_Author_Renderer_PostCount
     */
    public function render(Varien_Object $row)
    {
        $author_id =  $row->getAuthorId();
        $postcount = Mage::getModel('flexibleblog/post')->getCollection()
                        ->addFieldToFilter('post_author', $author_id);
        if($postcount->count()){
            return $postcount->count();
        } else {
            return '0';
        }
    }
}
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
 * Adminhtml_Comment_Renderer_Post block
 *
 * @category   Pixlogix
 * @package    Pixlogix_Flexibleblog
 * @author     Pixlogix Team <support@pixlogix.com>
 */

class Pixlogix_Flexibleblog_Block_Adminhtml_Comment_Renderer_Post extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    /**
     * To render post link on Comment Grid
     *
     * @return Pixlogix_Flexibleblog_Block_Adminhtml_Comment_Renderer_Post
     */
    public function render(Varien_Object $row)
    {
        //retriving form_id and result id from result grid collection
        $postid = $row->getData($this->getColumn()->getIndex());

        //retriving data from database by post_id
        $_pullPost = Mage::getModel('flexibleblog/post')->load($postid);

        $post_edit_url = Mage::helper('adminhtml')->getUrl('adminhtml/flexibleblog_post/edit', array('id' => $postid));
        $post = '<a target="_blank" rel="external" href="'.$post_edit_url.'">'.$_pullPost->getPostTitle().'</a>';
        if ($postid)
            return $post;
        else
            return '';
    }
}
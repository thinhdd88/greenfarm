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
 * Adminhtml_Author_Renderer_ImageGrid block
 *
 * @category   Pixlogix
 * @package    Pixlogix_Flexibleblog
 * @author     Pixlogix Team <support@pixlogix.com>
 */

class Pixlogix_Flexibleblog_Block_Adminhtml_Author_Renderer_ImageGrid extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    /**
     * Renderer class to display Author "Image" preview on Author Grid
     *
     * @return Pixlogix_Flexibleblog_Block_Adminhtml_Post_Renderer_ImageGrid
     */
    public function render(Varien_Object $row)
    {
        $postImage = $row->getData($this->getColumn()->getIndex());
        if($postImage)
        {
            $post_image_url = Mage::getSingleton('flexibleblog/image')->getPostImagePath($postImage);
            $value = '<img src="' . $post_image_url . '" width="100" height="100" />';
        }
        else
        {
            $value = '';
	}
        return $value;
    }
}
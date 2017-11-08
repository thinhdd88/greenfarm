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
 * Post Author block
 *
 * @category   Pixlogix
 * @package    Pixlogix_Flexibleblog
 * @author     Pixlogix Team <support@pixlogix.com>
 */

class Pixlogix_Flexibleblog_Block_Post_Author extends Pixlogix_Flexibleblog_Block_Abstract
{
    /**
     * _prepareLayout for Collection
     *
     * @return Pixlogix_Flexibleblog_Block_Post_Author
     */
    protected function _prepareLayout()
    {
	parent::_prepareLayout();
    }

    /**
     * set author data
     *
     * @return Pixlogix_Flexibleblog_Block_Post_Author
     */
    public function getAuthorData($authorId)
    {
        $collection = Mage::getModel('flexibleblog/author')
                        ->getCollection()
                        ->addFieldToFilter('author_id', array('eq' => $authorId ))
                        ->getFirstItem();
        return $collection;
    }

    /**
     * set author avatar image
     *
     * @return Pixlogix_Flexibleblog_Block_Post_Author
     */
    public function getAuthorAvatarImg($authorId, $width='', $height='')
    {
        $width = $width ? $width : 100;
        $height = $height ? $height : '';
        $collection = Mage::getModel('flexibleblog/author')
                ->getCollection()
                ->addFieldToFilter('author_id', array('eq' => $authorId ))
                ->getFirstItem();
        $authorAvatar = $collection->getAuthorAvatar() ? $collection->getAuthorAvatar() : 'default_avatar.png';
        $avatarPath = Mage::helper('flexibleblog')->resizeImage($authorAvatar, $width, $height,'flexibleblog');
        $image = '<img src="'.$avatarPath.'" alt="'.$collection->getAuthorName().'"/>';
        return $image;
    }
}
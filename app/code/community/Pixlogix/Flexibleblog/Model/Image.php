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
 * Flexibleblog Image model
 *
 * @category   Pixlogix
 * @package    Pixlogix_Flexibleblog
 * @author     Pixlogix Team <support@pixlogix.com>
 */

class Pixlogix_Flexibleblog_Model_Image extends Varien_Object
{
    /**
     * set Image Post Image Name
     *
     * @return Pixlogix_Flexibleblog_Model_Image
     */
    public function getImagePostImageName($postId)
    {
        if($postId)
        {
            $collection = Mage::getModel('flexibleblog/post')->getCollection()
                                    ->addFieldToSelect('post_id')
                                    ->addFieldToSelect('post_image')
                                    ->addFieldToSelect('post_title')
                                    ->addFieldToFilter('post_status', array('eq' => 1 ))
                                    ->addFieldToFilter('post_id', array('eq' => $postId ))
                                    ->getFirstItem();
            return $collection->getPostImage();
        } 
        else
        {
            return false;
        }
    }

    /**
     * set Post Image Path
     *
     * @return Pixlogix_Flexibleblog_Model_Image
     */
    public function getPostImagePath($image)
    {
        $value = '';
        if($image)
        {
            $value = Mage::getBaseUrl('media').'flexibleblog/' .$image;
        }
        return $value;
    }

    /**
     * set Post Thumb on list page
     *
     * @return Pixlogix_Flexibleblog_Model_Image
     */
    public function getPostThumb($postId, $width='', $height = '')
    {
        $defaultImage   = Mage::helper('flexibleblog')->imageSize();
        $width          = $width ? $width : ( ($defaultImage['width'])? $defaultImage['width'] : '900');
        $height         = $height ? $height : $defaultImage['height'];
        $imageModel     = Mage::getModel('flexibleblog/image');
        if($postId)
        {
                $imageName = $imageModel->getImagePostImageName($postId);
                if($imageName)
                {
                    $imageAlt   = Mage::getModel('flexibleblog/post')->getTitle($postId);
                    $imagePath  = Mage::helper('flexibleblog')->resizeImage($imageName,$width,$height,'flexibleblog');
                    $image      = '<img src="'.$imagePath.'" alt="'.$imageAlt.'"/>';
                    return $image;
                }
                else
                {
                    return false;
                }
        }
        return false;
    }

    /**
     * set Post Thumb on latest post widget
     *
     * @return Pixlogix_Flexibleblog_Model_Image
     */
    public function getLatestPostThumb($postId, $width='', $height = '')
    {
        $defaultImage   = Mage::helper('flexibleblog')->latestPostImageSize();
        $width          = $width ? $width : ( ($defaultImage['width'])? $defaultImage['width'] : '900');
        $height         = $height ? $height : $defaultImage['height'];

        $imageModel     = Mage::getModel('flexibleblog/image');
        if($postId)
        {
            $imageName = $imageModel->getImagePostImageName($postId);
            if($imageName){
                $imageAlt   = Mage::getModel('flexibleblog/post')->getTitle($postId);
                $imagePath  = Mage::helper('flexibleblog')->resizeImage($imageName,$width,$height,'flexibleblog');
                $image      = '<img src="'.$imagePath.'" alt="'.$imageAlt.'"/>';
                return $image;
            }
            else
            {
                return false;
            }
        }
        return false;
    }
    /**
     * set Image on detail page
     *
     * @return Pixlogix_Flexibleblog_Model_Image
     */
    public function getImage($postId, $width='', $height = '')
    {
            $defaultImage   = Mage::helper('flexibleblog')->postImageSize();
            $width          = $width ? $width : ( ($defaultImage['width'])? $defaultImage['width'] : '900');
            $height         = $height ? $height : $defaultImage['height'];
            $imageModel     = Mage::getModel('flexibleblog/image');
            if($postId)
            {
                $imageName  = $imageModel->getImagePostImageName($postId);
                if($imageName)
                {
                    $imagePath  = $imageModel->getPostImagePath($imageName);
                    $imageAlt   = Mage::getModel('flexibleblog/post')->getTitle($postId);
                    $imagePath  = Mage::helper('flexibleblog')->resizeImage($imageName,$width,$height,'flexibleblog');
                    //$image      = '<img src="'.$imagePath.'" alt="'.$imageAlt.'" />';
                    $image      = $imagePath;
                    return $image;
                }
                else
                {
                    return false;
                }
            }
            return false;
    }

    /**
     * set Image File Name
     *
     * @return Pixlogix_Flexibleblog_Model_Image
     */
    public function getImageFilename($imagename,$width,$height)
    {
        $pos        = strrpos($imagename,'.');
        $ext        = substr($imagename,$pos,strlen($imagename)+1);
        $newImageName='';
        if($pos)
        {
            $append[] = substr($imagename,0,$pos);
        }
        if(isset($width) && !empty($width))
        {
            $append[] = $width;
        }
        if(isset($height) && !empty($height))
        {
            $append[]= $height;
        }
        $newImageName = implode('-',$append);
        return $newImageName = $newImageName.$ext;
    }
}
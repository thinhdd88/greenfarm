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
 * Flexibleblog Tag model
 *
 * @category   Pixlogix
 * @package    Pixlogix_Flexibleblog
 * @author     Pixlogix Team <support@pixlogix.com>
 */

class Pixlogix_Flexibleblog_Model_Tag extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('flexibleblog/tag');
    }

    /**
     * set Tag Url
     *
     * @return Pixlogix_Flexibleblog_Model_Tag
     */
    public function getTagUrl($key)
    {
        $url = Mage::helper('flexibleblog')->customBlogUrl();
        return Mage::getUrl($url.'/tag/'.$key);
    }

    /**
     * set All Tag List
     *
     * @return Pixlogix_Flexibleblog_Model_Tag
     */
    public function getAllTagList()
    {
        $collection = Mage::getModel('flexibleblog/tag')->getCollection();
        $arrTags = '';
        foreach($collection as $tags)
        {
                $arrTags[] =$tags->getTagId();
        }
        return $arrTags;

    }

    /**
     * set All Tag Name List
     *
     * @return Pixlogix_Flexibleblog_Model_Tag
     */
    public function getAllTagNameList()
    {
        $collection = Mage::getModel('flexibleblog/tag')->getCollection();
        $arrTags = array();
        foreach($collection as $tags)
        {
            $arrTags[] =$tags->getTagName();
        }
        return $arrTags;
    }

    /**
     * set Post Tags
     *
     * @return Pixlogix_Flexibleblog_Model_Tag
     */
    public function getPostTags($postId)
    {
        $collection = Mage::getModel('flexibleblog/post')->load($postId);
        $tags       = $collection->getPostTags();
        $arrTags    = explode(',',$tags);
        $arrTagInfo = array();

        if(count($arrTags) > 0 )
        {
            foreach($arrTags as $tags)
            {
                $tagcollection  = Mage::getModel('flexibleblog/tag')->load($tags);
                $arrTagInfo[]   = $tagcollection->getTagName();
            }
        }
        return $arrTagInfo;
    }

    /**
     * set Tag Id By Field
     *
     * @return Pixlogix_Flexibleblog_Model_Tag
     */
    public function getTagIdByField($fieldName,$fieldValue)
    {
        $fieldName  = trim($fieldName);
        $collection = Mage::getModel('flexibleblog/tag')
                        ->getCollection()
                        ->addFieldToFilter($fieldName, array('eq'=> $fieldValue))
                        ->getFirstItem();

        if( $collection->getTagId() )
        {
            return $collection->getTagId();
        }
        else
        {
            return '';
        }
    }

    /**
     * set Tag By Url
     *
     * @return Pixlogix_Flexibleblog_Model_Tag
     */
    public function getTagByUrl($url)
    {
        $collection  = Mage::getModel('flexibleblog/tag')
                ->getCollection()
                ->addFieldToFilter('tag_url_key', array('eq' => $url ))
                ->getFirstItem();
        return $collection->getTagId();
    }

    /**
     * set Tag Id By Name
     *
     * @return Pixlogix_Flexibleblog_Model_Tag
     */
    public function getTagIdByName($name)
    {
        $name       = trim($name);
        $collection = Mage::getModel('flexibleblog/tag')
                        ->getCollection()
                        ->addFieldToFilter('tag_name', array('eq'=> $name))
                        ->getFirstItem();
        return $collection->getTagId();
    }

    /**
     * set New Tag Entry
     *
     * @return Pixlogix_Flexibleblog_Model_Tag
     */
    public function getNewTagEntry($tagList)
    {
        $tagModel       = Mage::getModel('flexibleblog/tag');
        $allTags        = $tagModel->getAllTagNameList(); //All tags from tags table
        $arrpostTags    = $tagModel->getPostTags($flexibleblogId); //Post old tags
        $newTagsEntry   = $tagList; //New post entry
        $arrNewTags     = explode(',',$tagList);
        $arrNewTags     = array_map('trim', $arrNewTags);

        //Indentify new tags to enter in database table
        $diffTags       = array_udiff($arrNewTags,$arrpostTags,'strcasecmp'); //Ignore case sensitivity in comparing
        $newEntryTags   = array_udiff($diffTags,$allTags,'strcasecmp'); //New tags which is not in database
        return $newEntryTags;
    }

    /**
     * set save Tags
     *
     * @return Pixlogix_Flexibleblog_Model_Tag
     */
    public function saveTags($tagList)
    {
        $newEntryTags = Mage::getModel('flexibleblog/tag')->getNewTagEntry($tagList);
        if(count($newEntryTags))
        {
            foreach($newEntryTags as $tag)
            {
                $tagPostData['tag_name']= $tag;
                $tagPostData['tag_url_key'] = Mage::helper('flexibleblog')->makeUrlKey($tag);
                Mage::getModel('flexibleblog/tag')->setData($tagPostData)->setTagName($tagPostData['tag_name'])->setTagUrlKey($tagPostData['tag_url_key'])->save();
            }
        }
    }

    /**
     * set unique tag url
     *
     * @return Pixlogix_Flexibleblog_Model_Tag
     */
    public function makeUniqueTagUrl( $title )
    {
        $url        = Mage::getSingleton('catalog/product')->formatUrlKey($title);
        $collection = Mage::getModel('flexibleblog/tag')
                        ->getCollection()
                        ->addFieldToFilter('tag_url_key', array('eq' => $url ));
        if($collection->count())
        {
            $url_key = $this->generateUniqueTagUrl($url);
        }
        else
        {
            $url_key = $url;
        }
        return $url_key;
    }
    
    /**
     * set generate unique tag url key
     *
     * @return Pixlogix_Flexibleblog_Model_Tag
     */
    public function generateUniqueTagUrl($url)
    {
        $counter = 1;
        $flag    = 0;
        do{
            $url_key    = $url.'_'.$counter;
            $collection = Mage::getModel('flexibleblog/tag')
                            ->getCollection()
                            ->addFieldToFilter('tag_url_key', array('eq' => $url_key ));
            if( !$collection->count() )
            {
                $flag = 1;
            }
            else
            {
                $counter++;
            }
        }while( $flag == 0 );
        return $url_key;
    }
}
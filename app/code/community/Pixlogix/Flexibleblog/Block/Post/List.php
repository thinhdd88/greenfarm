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
 * Post List block
 *
 * @category   Pixlogix
 * @package    Pixlogix_Flexibleblog
 * @author     Pixlogix Team <support@pixlogix.com>
 */

class Pixlogix_Flexibleblog_Block_Post_List extends Pixlogix_Flexibleblog_Block_Abstract
{
    /**
     * Set Collection of posts data
     *
     * @return Pixlogix_Flexibleblog_Block_Post_List
     */
    public function __construct()
    {
        parent::__construct();
        $collection = Mage::getModel('flexibleblog/post')->getCollection();
        $collection->addFieldToFilter( 'post_publish_date', array('lteq' => Mage::getModel('core/date')->gmtDate() ) );
        $collection->addFieldToFilter( 'post_status', array('eq' => 1 ) );
        if($this->getRequest()->getControllerName() == 'archive')
        {
            if($this->getRequest()->getParam('year') && $this->getRequest()->getParam('month'))
            {
                $dates[] = Mage::getModel('flexibleblog/post')->getArchiveDates($this->getRequest()->getParam('year'),$this->getRequest()->getParam('month'));
                $collection->addFieldToFilter('post_created_time', $dates);
            }
        }
        elseif($this->getRequest()->getControllerName() == 'category')
        {
            if($this->getRequest()->getParam('cat_url'))
            {
                $cat_id[] = Mage::getModel('flexibleblog/category')->getCategoryByUrl($this->getRequest()->getParam('cat_url'));
            }else{
                $cat_id[] = $this->getRequest()->getParam('cat_id');
            }
            $collection->addFieldToFilter( 'post_categories', array('finset' => $cat_id ) );
        }
        elseif($this->getRequest()->getControllerName() == 'author')
        {
            if($this->getRequest()->getParam('author_url'))
            {
                $author_id[] = Mage::getModel('flexibleblog/author')->getAuthorByUrl($this->getRequest()->getParam('author_url'));
            }else{
                $author_id[] = $this->getRequest()->getParam('author_id');
            }
            $collection->addFieldToFilter( 'post_author', array('finset' => $author_id ) );
        }
        elseif($this->getRequest()->getControllerName() == 'tag')
        {
            if($this->getRequest()->getParam('tag_url'))
            {
                $tag_url[] = Mage::getModel('flexibleblog/tag')->getTagByUrl($this->getRequest()->getParam('tag_url'));
            }
            $collection->addFieldToFilter( 'post_tags', array('finset' => $tag_url ) );
        }
	elseif($this->getRequest()->getControllerName() == 'search')
        {
            $search_item = $this->getRequest()->getParam('s');
            $collection->addFieldToFilter( array('post_title', 'post_description', 'post_short_description'),
                                            array(
                                                array('like'=>'%'.$search_item.'%'),
                                                array('like'=>'%'.$search_item.'%'),
                                                array('like'=>'%'.$search_item.'%')
                                            ) );
        }

        if(!Mage::app()->isSingleStoreMode())
        {
            $storeId    = Mage::app()->getStore()->getStoreId();
            $collection = $collection->addFieldToFilter( 'post_store_view',array('finset' => array( $storeId ) ) );
        }
        $collection->setOrder('post_publish_date','DESC');
        $this->setCollection($collection);
    }

    /**
     * _prepareLayout for Collection
     *
     * @return Pixlogix_Flexibleblog_Block_Post_List
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $pager = $this->getLayout()->createBlock('page/html_pager', 'custom.pager');
        if($per_page = Mage::helper('flexibleblog')->getPostPerPage())
        {
            $arr = explode(",",$per_page);
            $array_val = array_combine($arr,$arr);
            $pager->setAvailableLimit($array_val);
        } else {
            $pager->setAvailableLimit(array(10=>10,20=>20,50=>50,'all'=>'all'));
        }
        $pager->setCollection($this->getCollection());
        $this->setChild('pager', $pager);
        $this->getCollection()->load();
        return $this;
    }

    /**
     * set pager html
     *
     * @return Pixlogix_Flexibleblog_Block_Post_List
     */
    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }

    /**
     * set list title
     *
     * @return Pixlogix_Flexibleblog_Block_Post_List
     */
    public function getListTitle()
    {
        if($this->getRequest()->getControllerName() == 'index')
        {
            $title = $this->__('Blog');
            $blog_title = Mage::getStoreConfig('flexibleblog_options/general/blog_title');
            if($blog_title){
                $title = $blog_title;
            }
        }
        elseif($this->getRequest()->getControllerName() == 'archive')
        {
            $archive = $this->getRequest()->getParam('year').'-'.$this->getRequest()->getParam('month');
            $archive_date = date('F Y', strtotime($archive));
            $title = $this->__('Month: %s', $archive_date);
        }
        elseif($this->getRequest()->getControllerName() == 'category')
        {
            $category = $this->getRequest()->getParam('cat_url');
            $collection = Mage::getModel('flexibleblog/category')
                    ->getCollection()
                    ->addFieldToFilter('category_url_key', array( 'eq' => $category))
                    ->getFirstItem();
            $category_title = $collection->getCategoryTitle();
            $title = $this->__('<p>Category Archives</p> %s', $category_title);
        }
        elseif($this->getRequest()->getControllerName() == 'author')
        {
            $author = $this->getRequest()->getParam('author_url');
            $collection = Mage::getModel('flexibleblog/author')
                    ->getCollection()
                    ->addFieldToFilter('author_url_key', array( 'eq' => $author))
                    ->getFirstItem();
            $author_name = $collection->getAuthorName();
            $title = $this->__('Author: %s', $author_name);
        }
        elseif($this->getRequest()->getControllerName() == 'tag')
        {
            $tag = $this->getRequest()->getParam('tag_url');
            $collection = Mage::getModel('flexibleblog/tag')
                    ->getCollection()
                    ->addFieldToFilter('tag_url_key', array( 'eq' => $tag))
                    ->getFirstItem();
            $tag_name = $collection->getTagName();
            $title = $this->__('Tag: %s', $tag_name);
        }
        return $title;
    }

    /**
     * set list description
     *
     * @return Pixlogix_Flexibleblog_Block_Post_List
     */
    public function getListDescription()
    {
        if($this->getRequest()->getControllerName() == 'index')
        {
            $description = Mage::getStoreConfig('flexibleblog_options/general/meta_description');
        }
        elseif($this->getRequest()->getControllerName() == 'category')
        {
            $category = $this->getRequest()->getParam('cat_url');
            $collection = Mage::getModel('flexibleblog/category')
                    ->getCollection()
                    ->addFieldToFilter('category_url_key', array( 'eq' => $category))
                    ->getFirstItem();
            $description = '';
            $cat_desc = $collection->getCategoryDescription();
            if($cat_desc){
                $description = Mage::helper('cms')->getBlockTemplateProcessor()->filter($cat_desc); //pass the variable which is use to display content of wysiwyg editor
            }
        }
        elseif($this->getRequest()->getControllerName() == 'author')
        {
            $author = $this->getRequest()->getParam('author_url');
            $collection = Mage::getModel('flexibleblog/author')
                    ->getCollection()
                    ->addFieldToFilter('author_url_key', array( 'eq' => $author))
                    ->getFirstItem();
            $description = '';
            $author_bio = $collection->getAuthorBio();
            if($author_bio){
                $description = Mage::helper('cms')->getBlockTemplateProcessor()->filter($author_bio); //pass the variable which is use to display content of wysiwyg editor
            }
        }
        return $description;
    }

    /**
     * set blog top link
     *
     * @return Pixlogix_Flexibleblog_Block_Post_List
     */
    public function addFlexibleblogTopLink()
    {
        $parentBlock = $this->getParentBlock(); //Create an object for getParentBlock() method
        $enabledFlexibleblog = $this->helper('flexibleblog')->enableFlexibleblog(); //Check if flexibleblog module is enabled
        $enabledTopLink = $this->helper('flexibleblog')->enableTopLink(); //Check if toplink is enabled
        if ($parentBlock && $enabledFlexibleblog && $enabledTopLink) {
            $text = Mage::helper('flexibleblog')->blogTitle();
            //Top link Display Text
            $url = Mage::helper('flexibleblog')->customBlogUrl();
            $position = 5;
            // @param string $text
            // @param string $url
            // @param string $text
            // @param boolean $prepare
            // @param array $urlParams
            // @param int $position
            // @return Mage_Page_Block_Template_Links
            $parentBlock->addLink($text, $url , $text, $prepare=true, $urlParams=array(), $position , null, 'class="top-link-flexibleblog"');
        }
        return $this;
    }

    /**
     * set blog footer link
     *
     * @return Pixlogix_Flexibleblog_Block_Post_List
     */
    public function addFlexibleblogFooterLink()
    {
            $parentBlock = $this->getParentBlock(); //Create an object for getParentBlock() method
            $enabledFlexibleblog = $this->helper('flexibleblog')->enableFlexibleblog(); //Check if flexibleblog module is enabled
            $enabledFooterLink = $this->helper('flexibleblog')->enableFooterLink(); //Check if toplink is enabled
            if ($parentBlock && $enabledFlexibleblog && $enabledFooterLink) {
            $text = Mage::helper('flexibleblog')->blogTitle();
            //Top link Display Text
            $url = Mage::helper('flexibleblog')->customBlogUrl();
            $position = 5;
            // @param string $text
            // @param string $url
            // @param string $text
            // @param boolean $prepare
            // @param array $urlParams
            // @param int $position
            // @return Mage_Page_Block_Template_Links
            $parentBlock->addLink($text, $url , $text, $prepare=true, $urlParams=array(), $position , null, 'class="footer-link-flexibleblog"');
        }
        return $this;
	}

    /**
     * set Jss and Css
     *
     * @return Pixlogix_Flexibleblog_Block_Post_List
     */
    public function addJsCss()
    {
        $parentBlock = $this->getParentBlock();
        $enabledFlexibleblog = $this->helper('flexibleblog')->enableFlexibleblog(); //Check if flexibleblog module is enabled
        $enabledjQuery = $this->helper('flexibleblog')->enabledjQuery(); //Check if jQuery library file is enabled
        if($enabledFlexibleblog){
            $parentBlock->addItem('skin_css','css/flexibleblog/flexibleblog.css');
            if($enabledjQuery){
                $parentBlock->addItem('skin_js','js/flexibleblog/jquery-2.1.1.min.js');
                $parentBlock->addItem('skin_js','js/flexibleblog/jquery-noconflict.js');
            }
            $parentBlock->addItem('skin_js','js/flexibleblog/general.js');
        }
    }
}
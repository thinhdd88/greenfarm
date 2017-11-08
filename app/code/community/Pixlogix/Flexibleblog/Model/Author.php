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
 * Flexibleblog Author model
 *
 * @category   Pixlogix
 * @package    Pixlogix_Flexibleblog
 * @author     Pixlogix Team <support@pixlogix.com>
 */

class Pixlogix_Flexibleblog_Model_Author extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('flexibleblog/author');
    }

    /**
     * Retrieve Author option value
     *
     * @return Pixlogix_Flexibleblog_Model_Author
     */
    public function getOptionArray()
    {
        $authorCollection   = Mage::getModel('flexibleblog/author')->getCollection();
        //$authorCollection->addFieldToFilter('author_status', array('eq'=> 1));
        $authorCollection->setOrder('author_id','asc');
        $authors            = array();
        if($authorCollection->count() > 0)
        {
            foreach ($authorCollection as $author)
            {
                $authors[] =array(
                    'value' => $author->getAuthorId(),
                    'label' => $author->getAuthorName(),
                );
            }
        } else {
            $authors = '';
        }
        return $authors;
    }

    /**
     * set Author By Url
     *
     * @return Pixlogix_Flexibleblog_Model_Author
     */
    public function getAuthorByUrl($url)
    {
        $collection  = Mage::getModel('flexibleblog/author')
                                    ->getCollection()
                                    ->addFieldToFilter('author_url_key', array('eq' => $url ))
                                    ->getFirstItem();
        return $collection->getAuthorId();
    }
}
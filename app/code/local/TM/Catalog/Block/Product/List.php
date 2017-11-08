<?php

class TM_Catalog_Block_Product_List extends Mage_Catalog_Block_Product_List
{
    /** @var TM_Catalog_Helper_Data */
    protected $_hlp;

    public function _prepareLayout()
    {
        $this->_hlp = Mage::helper('tm_catalog/data');
        return parent::_prepareLayout();
    }

    /**
     * @return Mage_Catalog_Model_Resource_Product_Collection
     */
    public function _getProductCollection(){
        $collection = parent::_getProductCollection();
        return $collection;
    }

    /**
     * @return Mage_Catalog_Model_Category
     */
    public function getCurrentCategory() {
        return $this->_hlp->getCurrentCategory();
    }

    public function getCurrentCategoryUrl() {
        $currentCat = $this->getCurrentCategory();
        if ($currentCat->getId() == TM_Data_Helper_Data::CAT_DEFAULT_ID) {
            $currentCat = $this->_hlp->getCategory(TM_Data_Helper_Data::CAT_SHOP_ID);
        }
        return $currentCat->getUrl();
    }

        public function getCategoryUrl($categoryId) {
            return $this->_hlp->getCategory($categoryId)->getUrl();
        }

        public function getCategoryName($categoryId) {
            return $this->_hlp->getCategory($categoryId)->getName();
        }

        /**
         * @param Mage_Catalog_Model_Product $product
         * @param $attrCode
         * @return array
         */
        public function getOptionIds($product, $attrCode) {
            return $this->_hlp->getOptionIds($product, $attrCode);
        }

        /**
         * @param Mage_Catalog_Model_Product $product
         * @param $attrCode
         * @param $optId
         * @return array
         */
        public function getOptionText($product, $attrCode, $optId) {
            return $this->_hlp->getOptionText($product, $attrCode, $optId);
        }

        public function normalize($text) {
            return $this->_hlp->normalize($text);
        }

}
<?php
class TM_Catalog_Helper_Data extends Mage_Core_Helper_Abstract {

    public function normalize($text) {
        return str_replace(' ', '-', strtolower($text));
    }

    /**
     * @param Mage_Catalog_Model_Product $product
     * @param $attrCode
     * @return array
     */
    public function getOptionIds($product, $attrCode) {
        if (empty($product->getData($attrCode))) {
            return array();
        }
        return explode(',', $product->getData($attrCode));
    }

    /**
     * @param Mage_Catalog_Model_Product $product
     * @param $attrCode
     * @param $optId
     * @return array
     */
    public function getOptionText($product, $attrCode, $optId) {
        if (!isset($this->_attributes[$attrCode])) {
            $this->_attributes[$attrCode] = $product->getResource()->getAttribute($attrCode);
        }
        if (!isset($this->_options[$optId])) {
            $this->_options[$optId] = $this->_attributes[$attrCode]->getSource()->getOptionText($optId);
        }
        return $this->_options[$optId];
    }

    public function getAttributeByCode($attributeCode) {
        /** @var Mage_Eav_Model_Config $eav_config */
        $eav_config = Mage::getSingleton('eav/config');
        return $eav_config->getAttribute(Mage_Catalog_Model_Product::ENTITY, $attributeCode);
    }

    public function getAttributeOptions($attributeCode) {
        $attribute = $this->getAttributeByCode($attributeCode);
        return $attribute->getSource()->getAllOptions();
    }

    /**
     * @return Mage_Catalog_Model_Category
     */
    public function getCurrentCategory() {
        if (!$this->_category) {
            $this->_category = $this->getLayer()->getCurrentCategory();
        }
        return $this->_category;
    }

    /**
     * @param $catId
     * @return Mage_Catalog_Model_Category
     */
    public function getCategory($catId) {
        if (!isset($this->_categories[$catId])) {
            $this->_categories[$catId] = Mage::getModel('catalog/category')->load($catId);
        }
        return $this->_categories[$catId];
    }

    /**
     * @param Mage_Catalog_Model_Product $product
     * @return bool
     */
    function isProductNew($product)
    {
        $newsFromDate = $product->getNewsFromDate();
        $newsToDate   = $product->getNewsToDate();
        if (!$newsFromDate && !$newsToDate) {
            return false;
        }
        return Mage::app()->getLocale()
            ->isStoreDateInInterval($product->getStoreId(), $newsFromDate, $newsToDate);
    }

    /**
     * @param Mage_Catalog_Model_Product $product
     * @return bool
     */
    function isProductBestseller($product)
    {
        if (in_array($product->getId(), $this->getBestSellerProduct())) {
            return true;
        }
        return false;
    }

    public function getBestSellerProduct() {
        if (!is_array($this->_bestSeller)) {
            $this->_bestSeller = array();
            /** @var TS_Catalog_Model_Resource_Bestsellers_Month_Collection $collection */
            $collection = Mage::getResourceModel('tm_catalog/bestsellers_month_collection');
            $collection->load();
            /** @var Mage_Adminhtml_Model_Report_Item $item */
            foreach ($collection as $item) {
                $this->_bestSeller[] = $item->getData('product_id');
            }
        }
        return $this->_bestSeller;
    }

    /**
     * @param Mage_Catalog_Model_Product $product
     * @return bool
     */
    function isProductDiscount($product)
    {
        return $product->getFinalPrice() < $product->getPrice();
    }


    /**
     *
     * @return Mage_Catalog_Model_Layer
     */
    public function getLayer()
    {
        $layer = Mage::registry('current_layer');
        if ($layer) {
            return $layer;
        }
        return Mage::getSingleton('catalog/layer');
    }
}
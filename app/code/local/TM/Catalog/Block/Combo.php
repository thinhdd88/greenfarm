<?php

class TM_Catalog_Block_Combo extends TM_Catalog_Block_Product_List
{
    function _construct()
    {
        parent::_construct(); // TODO: Change the autogenerated stub
    }

    /**
     * @return Mage_Catalog_Model_Resource_Product_Collection
     */
    public function _getProductCollection(){
        $this->setCategoryId($this->getCurrentCategory()->getId());
        $collection = parent::_getProductCollection();
        $collection->setPage(1, 12);
        return $collection;
    }

    /**
     * @return Mage_Catalog_Model_Category
     */
    public function getCurrentCategory() {
        return Mage::getModel('catalog/category')->load(TM_Data_Helper_Data::CAT_COMBO_ID);
    }
}
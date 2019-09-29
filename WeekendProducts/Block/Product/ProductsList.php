<?php

namespace SilentNight\WeekendProducts\Block\Product;

/***
 * Class ProductsList
 * @package SilentNight\WeekendProducts\Block\Product
 */
class ProductsList extends \Magento\CatalogWidget\Block\Product\ProductsList
{
    /**
     * @return \Magento\Catalog\Model\ResourceModel\Product\Collection
     */
    public function createCollection()
    {
        /** @var $collection \Magento\Catalog\Model\ResourceModel\Product\Collection */
        $collection = $this->productCollectionFactory->create();

        if ($this->getData('store_id') !== null) {
            $collection->setStoreId($this->getData('store_id'));
        }
        $collection->setVisibility($this->catalogProductVisibility->getVisibleInCatalogIds());

        $collection = $this->_addProductAttributesAndPrices($collection)
            ->addCategoriesFilter(['eq' => $this->getCategoryId()])
            ->addStoreFilter()
            ->addAttributeToSort('created_at', 'desc')
            ->setPageSize($this->getPageSize())
            ->setCurPage($this->getRequest()->getParam($this->getData('page_var_name'), 1));

        $collection->getSelect()->orderRand('entity_id');
        return $collection;
    }

    /**
     * @return mixed
     */
    public function getCategoryId() {

        $rewriteData = explode('/', $this->getData('id_path'));

        if (!isset($rewriteData[0]) || !isset($rewriteData[1])) {
            throw new \RuntimeException('Wrong id_path structure.');
        }
        return $rewriteData[1];
    }
}

<?php
/**
 * @author andy
 * @email andyworkbase@gmail.com
 * @team MageCloud
 * @package MageCloud_Faq
 */
namespace MageCloud\Faq\Model\ResourceModel\Faq;

use MageCloud\Faq\Api\Data\FaqInterface;
use MageCloud\Faq\Model\ResourceModel\AbstractCollection;
use MageCloud\Faq\Model\Faq;

/**
 * Class Collection
 * @package MageCloud\Faq\Model\ResourceModel\Faq
 */
class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'faq_id';

    /**
     * Event prefix
     *
     * @var string
     */
    protected $_eventPrefix = 'magecloud_faq_collection';

    /**
     * Event object
     *
     * @var string
     */
    protected $_eventObject = 'faq_collection';

    /**
     * Perform operations after collection load
     *
     * @return $this
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    protected function _afterLoad()
    {
        $entityMetadata = $this->metadataPool->getMetadata(FaqInterface::class);

        $this->performAfterLoad(
            'magecloud_faq_store',
            'magecloud_faq_catalog_category',
            $entityMetadata->getLinkField()
        );

        return parent::_afterLoad();
    }

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\MageCloud\Faq\Model\Faq::class, \MageCloud\Faq\Model\ResourceModel\Faq::class);
        $this->_map['fields']['store'] = 'store_table.store_id';
        $this->_map['fields']['category'] = 'category_table.category_id';
        $this->_map['fields']['faq_id'] = 'main_table.faq_id';
    }

    /**
     * Returns pairs faq_id - title
     *
     * @return array
     */
    public function toOptionArray()
    {
        return $this->_toOptionArray('faq_id', 'title');
    }

    /**
     * Add filter by store
     *
     * @param int|array|\Magento\Store\Model\Store $store
     * @param bool $withAdmin
     * @return $this
     */
    public function addStoreFilter($store, $withAdmin = true)
    {
        $this->performAddStoreFilter($store, $withAdmin);

        return $this;
    }

    /**
     * Add filter by category
     *
     * @param int|array|\Magento\Catalog\Model\Category $category
     * @return $this
     */
    public function addCategoryFilter($category)
    {
        $this->performAddCategoryFilter($category);

        return $this;
    }

    /**
     * @return $this
     */
    public function addIsActiveFilter()
    {
        $this->addFieldToFilter('is_active', ['eq' => Faq::STATUS_ENABLED]);
        return $this;
    }

    /**
     * @return $this
     */
    public function addSortOrder()
    {
        $this->setOrder('sort_order', \Magento\Framework\Data\Collection::SORT_ORDER_ASC);
        return $this;
    }

    /**
     * Join relation tables if there are in the filter
     *
     * @return void
     * @throws \Exception
     */
    protected function _renderFiltersBefore()
    {
        $entityMetadata = $this->metadataPool->getMetadata(FaqInterface::class);
        $this->joinRelationTables(
            'magecloud_faq_store',
            'magecloud_faq_catalog_category',
            $entityMetadata->getLinkField()
        );
    }
}
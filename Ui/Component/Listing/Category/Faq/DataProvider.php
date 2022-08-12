<?php
/**
 * @author andy
 * @email andyworkbase@gmail.com
 * @team MageCloud
 * @package MageCloud_Faq
 */
namespace MageCloud\Faq\Ui\Component\Listing\Category\Faq;

use MageCloud\Faq\Model\ResourceModel\Faq\Grid\Collection as GridCollection;
use MageCloud\Faq\Model\ResourceModel\Faq\Grid\CollectionFactory;
use Magento\Store\Model\Store;
use Magento\Store\Model\StoreManager;
use Magento\Framework\Api\Filter;
use Magento\Framework\App\RequestInterface;
use Magento\Ui\DataProvider\AbstractDataProvider;

/**
 * Class DataProvider
 * @package MageCloud\Faq\Ui\Component\Listing\Faq\Category\Faq
 */
class DataProvider extends AbstractDataProvider
{
    /**
     * @var RequestInterface $request,
     */
    private $request;

    /**
     * @var StoreManager
     */
    private $storeManager;

    /**
     * DataProvider constructor.
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param RequestInterface $request
     * @param StoreManager $storeManager
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        RequestInterface $request,
        StoreManager $storeManager,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->collection = $collectionFactory->create();
        $this->request = $request;
        $this->storeManager = $storeManager;
    }

    /**
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getData(): array
    {
        /** @var GridCollection $collection */
        $collection = $this->getCollection();
        $data['items'] = [];
        if ($categoryId = $this->request->getParam('category_id')) {
            $collection->addCategoryFilter($categoryId);
        }
        $storeId = $this->request->getParam('store_id', Store::DEFAULT_STORE_ID);
        $collection->addStoreFilter($storeId);
        $data = $collection->toArray();

        return $data;
    }

    /**
     * Add search filter to collection
     *
     * @param Filter $filter
     * @return void
     */
    public function addFilter(Filter $filter): void
    {
        /** @var GridCollection $collection */
        $collection = $this->getCollection();
        $collection->addFieldToFilter(
            $filter->getField(),
            [$filter->getConditionType() => $filter->getValue()]
        );
    }
}
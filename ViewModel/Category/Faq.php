<?php
/**
 * @author andy
 * @email andyworkbase@gmail.com
 * @team MageCloud
 * @package MageCloud_Faq
 */
namespace MageCloud\Faq\ViewModel\Category;

use Magento\Framework\DataObject;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use MageCloud\Faq\Model\ResourceModel\Faq\CollectionFactory as FaqCollectionFactory;
use Magento\Framework\Registry;
use MageCloud\Faq\Helper\Data as HelperData;
use Magento\Framework\Serialize\Serializer\Json;

/**
 * Class Faq
 * @package MageCloud\Faq\ViewModel\Category
 */
class Faq extends DataObject implements ArgumentInterface
{
    const SCHEMA_CONTEXT = 'http://schema.org';
    const SCHEMA_CONTEXT_TYPE = 'FAQPage';

    /**
     * @var FaqCollectionFactory
     */
    private $faqCollectionFactory;

    /**
     * @var Registry
     */
    private $registry;

    /**
     * @var HelperData
     */
    private $helperData;

    /**
     * @var Json
     */
    private $json;

    /**
     * @var array
     */
    private $faqCollection = [];

    /**
     * Faq constructor.
     * @param FaqCollectionFactory $faqCollectionFactory
     * @param Registry $registry
     * @param HelperData $helperData
     * @param Json $json
     * @param array $data
     */
    public function __construct(
        FaqCollectionFactory $faqCollectionFactory,
        Registry $registry,
        HelperData $helperData,
        Json $json,
        array $data = []
    ) {
        parent::__construct($data);
        $this->faqCollectionFactory = $faqCollectionFactory;
        $this->registry = $registry;
        $this->helperData = $helperData;
        $this->json = $json;
    }

    /**
     * @param null $store
     * @return \Magento\Store\Api\Data\StoreInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getStore($store = null)
    {
        return $this->helperData->getStore($store);
    }

    /**
     * @param null $store
     * @return bool
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function isEnabled($store = null)
    {
        return $this->helperData->isEnabled($store);
    }

    /**
     * @param null $store
     * @return bool
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function isEnabledSchema($store = null)
    {
        return $this->helperData->isEnabledSchema($store);
    }

    /**
     * @return \Magento\Catalog\Model\Category|null
     */
    public function getCurrentCategory()
    {
        return $this->registry->registry('current_category') ?? $this->registry->registry('category');
    }

    /**
     * @return mixed|string
     */
    public function getTitle()
    {
        return trim($this->getCurrentCategory()->getData('magecloud_faq_title'))
            ?? $this->getCurrentCategory()->getName() . ' FAQ';
    }

    /**
     * @return \MageCloud\Faq\Model\ResourceModel\Faq\Collection|null
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getFaqCollection()
    {
        $cacheKey = sprintf('cat_%', $this->getCurrentCategory()->getId());
        if (!isset($this->faqCollection[$cacheKey])) {
            /** @var \MageCloud\Faq\Model\ResourceModel\Faq\Collection $faqCollection */
            $faqCollection = $this->faqCollectionFactory->create();
            $faqCollection->addStoreFilter($this->getStore())
                ->addCategoryFilter($this->getCurrentCategory())
                ->addIsActiveFilter()
                ->addSortOrder();
            $this->faqCollection[$cacheKey] = $faqCollection;
        }
        return $this->faqCollection[$cacheKey];
    }

    /**
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getSchema()
    {
        if (!$this->isEnabledSchema()) {
            return '';
        }

        $schemaItems = [];
        foreach ($this->getFaqCollection() as $item) {
            /** @var \MageCloud\Faq\Model\Faq $item */
            $schemaItems[] = [
                '@type' => 'Question',
                'name' => $item->getTitle(),
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => $item->getContent()
                ]
            ];
        }
        if (count($schemaItems)) {
            $schema = [
                '@context' => self::SCHEMA_CONTEXT,
                '@type' => self::SCHEMA_CONTEXT_TYPE,
                'mainEntity' => $schemaItems
            ];
            return "<script type=\"application/ld+json\">{$this->json->serialize($schema)}</script>";
        }

        return '';
    }
}
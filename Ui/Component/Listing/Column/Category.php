<?php
/**
 * @author andy
 * @email andyworkbase@gmail.com
 * @team MageCloud
 * @package MageCloud_Faq
 */
namespace MageCloud\Faq\Ui\Component\Listing\Column;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory as CategoryCollectionFactory;

/**
 * Class FaqActions
 * @package MageCloud\Faq\Ui\Component\Listing\Column
 */
class Category extends Column
{
    /**
     * @var CategoryCollectionFactory
     */
    private $categoryCollectionFactory;

    /**
     * @var string
     */
    protected $categoryKey;

    /**
     * Category constructor.
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param CategoryCollectionFactory $categoryCollectionFactory
     * @param array $components
     * @param array $data
     * @param string $categoryKey
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        CategoryCollectionFactory $categoryCollectionFactory,
        array $components = [],
        array $data = [],
        $categoryKey = 'category_id'
    ) {
        $this->categoryCollectionFactory = $categoryCollectionFactory;
        $this->categoryKey = $categoryKey;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * @inheritDoc
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                $item[$this->getData('name')] = $this->prepareItem($item);
            }
        }

        return $dataSource;
    }

    /**
     * @param $item
     * @return \Magento\Framework\Phrase|string
     */
    private function prepareItem($item)
    {
        $content = '';
        if (!empty($item[$this->categoryKey])) {
            $origCategories = $item[$this->categoryKey];
        }

        if (empty($origCategories)) {
            return __('All Categories');
        }
        if (!is_array($origCategories)) {
            $origCategories = [$origCategories];
        }

        /** @var \Magento\Catalog\Model\ResourceModel\Category\Collection $categoryCollection */
        $categoryCollection = $this->categoryCollectionFactory->create();
        $categoryCollection->addIdFilter($origCategories)
            ->addNameToResult();
        $categories = [];
        foreach ($categoryCollection as $category) {
            $categories[] = '<span class="admin__action-multiselect-crumb"><span>'
                . (string)$category->getName() . '</span></span>';
        }
        if (!empty($categories)) {
            $content .= implode(' ', $categories);
        }
        return $content;
    }
}

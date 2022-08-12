<?php
/**
 * @author andy
 * @email andyworkbase@gmail.com
 * @team MageCloud
 * @package MageCloud_Faq
 */
namespace MageCloud\Faq\Model\ResourceModel\Faq\Relation\Category;

use MageCloud\Faq\Model\ResourceModel\Faq;
use Magento\Framework\EntityManager\Operation\ExtensionInterface;

/**
 * Class ReadHandler
 * @package MageCloud\Faq\Model\ResourceModel\Faq\Relation\Category
 */
class ReadHandler implements ExtensionInterface
{
    /**
     * @var Faq
     */
    protected $resourceFaq;

    /**
     * ReadHandler constructor.
     * @param Faq $resourceFaq
     */
    public function __construct(
        Faq $resourceFaq
    ) {
        $this->resourceFaq = $resourceFaq;
    }

    /**
     * @param object $entity
     * @param array $arguments
     * @return object
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute($entity, $arguments = [])
    {
        if ($id = (int)$entity->getId()) {
            $categories = $this->resourceFaq->lookupCategoryIds($id);
            $entity->setData('category_id', $categories);
            $entity->setData('categories', $categories);
        }
        return $entity;
    }
}

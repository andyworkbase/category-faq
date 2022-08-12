<?php
/**
 * @author andy
 * @email andyworkbase@gmail.com
 * @team MageCloud
 * @package MageCloud_Faq
 */
namespace MageCloud\Faq\Model\ResourceModel\Faq\Relation\Store;

use MageCloud\Faq\Model\ResourceModel\Faq;
use Magento\Framework\EntityManager\Operation\ExtensionInterface;

/**
 * Class ReadHandler
 * @package MageCloud\Faq\Model\ResourceModel\Faq\Relation\Store
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
            $stores = $this->resourceFaq->lookupStoreIds($id);
            $entity->setData('store_id', $stores);
            $entity->setData('stores', $stores);
        }
        return $entity;
    }
}

<?php
/**
 * @author andy
 * @email andyworkbase@gmail.com
 * @team MageCloud
 * @package MageCloud_Faq
 */
namespace MageCloud\Faq\Model\ResourceModel\Faq\Relation\Store;

use Magento\Framework\EntityManager\Operation\ExtensionInterface;
use MageCloud\Faq\Api\Data\FaqInterface;
use MageCloud\Faq\Model\ResourceModel\Faq;
use Magento\Framework\EntityManager\MetadataPool;

/**
 * Class SaveHandler
 * @package MageCloud\Faq\Model\ResourceModel\Faq\Relation\Store
 */
class SaveHandler implements ExtensionInterface
{
    /**
     * @var MetadataPool
     */
    protected $metadataPool;

    /**
     * @var Faq
     */
    protected $resourceFaq;

    /**
     * SaveHandler constructor.
     * @param MetadataPool $metadataPool
     * @param Faq $resourceFaq
     */
    public function __construct(
        MetadataPool $metadataPool,
        Faq $resourceFaq
    ) {
        $this->metadataPool = $metadataPool;
        $this->resourceFaq = $resourceFaq;
    }

    /**
     * @param object $entity
     * @param array $arguments
     * @return object
     * @throws \Exception
     */
    public function execute($entity, $arguments = [])
    {
        $entityMetadata = $this->metadataPool->getMetadata(FaqInterface::class);
        $linkField = $entityMetadata->getLinkField();

        $connection = $entityMetadata->getEntityConnection();

        $oldStores = $this->resourceFaq->lookupStoreIds((int)$entity->getId());
        $newStores = (array)$entity->getStoreId();

        $table = $this->resourceFaq->getTable('magecloud_faq_store');

        $delete = array_diff($oldStores, $newStores);
        if ($delete) {
            $where = [
                $linkField . ' = ?' => (int)$entity->getData($linkField),
                'store_id IN (?)' => $delete,
            ];
            $connection->delete($table, $where);
        }

        $insert = array_diff($newStores, $oldStores);
        if ($insert) {
            $data = [];
            foreach ($insert as $storeId) {
                $data[] = [
                    $linkField => (int)$entity->getData($linkField),
                    'store_id' => (int)$storeId,
                ];
            }
            $connection->insertMultiple($table, $data);
        }

        return $entity;
    }
}

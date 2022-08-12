<?php
/**
 * @author andy
 * @email andyworkbase@gmail.com
 * @team MageCloud
 * @package MageCloud_Faq
 */
namespace MageCloud\Faq\Model\ResourceModel\Faq\Relation\Category;

use Magento\Framework\EntityManager\Operation\ExtensionInterface;
use MageCloud\Faq\Api\Data\FaqInterface;
use MageCloud\Faq\Model\ResourceModel\Faq;
use Magento\Framework\EntityManager\MetadataPool;

/**
 * Class SaveHandler
 * @package MageCloud\Faq\Model\ResourceModel\Faq\Relation\Category
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

        $oldCategories = $this->resourceFaq->lookupCategoryIds((int)$entity->getId());
        $newCategories = (array)$entity->getCategoryId();

        $table = $this->resourceFaq->getTable('magecloud_faq_catalog_category');

        $delete = array_diff($oldCategories, $newCategories);
        if ($delete) {
            $where = [
                $linkField . ' = ?' => (int)$entity->getData($linkField),
                'category_id IN (?)' => $delete,
            ];
            $connection->delete($table, $where);
        }

        $insert = array_diff($newCategories, $oldCategories);
        if ($insert) {
            $data = [];
            foreach ($insert as $storeId) {
                $data[] = [
                    $linkField => (int)$entity->getData($linkField),
                    'category_id' => (int)$storeId,
                ];
            }
            $connection->insertMultiple($table, $data);
        }

        return $entity;
    }
}

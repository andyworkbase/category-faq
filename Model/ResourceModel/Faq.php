<?php
/**
 * @author andy
 * @email andyworkbase@gmail.com
 * @team MageCloud
 * @package MageCloud_Faq
 */
namespace MageCloud\Faq\Model\ResourceModel;

use MageCloud\Faq\Api\Data\FaqInterface;
use Magento\Framework\DB\Select;
use Magento\Framework\EntityManager\EntityManager;
use Magento\Framework\EntityManager\MetadataPool;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;
use Magento\Store\Model\Store;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class Faq
 * @package MageCloud\Faq\Model\ResourceModel
 */
class Faq extends AbstractDb
{
    /**
     * Store manager
     *
     * @var StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var MetadataPool
     */
    protected $metadataPool;

    /**
     * @param Context $context
     * @param StoreManagerInterface $storeManager
     * @param EntityManager $entityManager
     * @param MetadataPool $metadataPool
     * @param string $connectionName
     */
    public function __construct(
        Context $context,
        StoreManagerInterface $storeManager,
        EntityManager $entityManager,
        MetadataPool $metadataPool,
        $connectionName = null
    ) {
        $this->_storeManager = $storeManager;
        $this->entityManager = $entityManager;
        $this->metadataPool = $metadataPool;
        parent::__construct($context, $connectionName);
    }

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('magecloud_faq', 'faq_id');
    }

    /**
     * @inheritDoc
     */
    public function getConnection()
    {
        return $this->metadataPool->getMetadata(FaqInterface::class)->getEntityConnection();
    }

    /**
     * Perform operations before object save
     *
     * @param AbstractModel $object
     * @return $this
     * @throws LocalizedException
     */
    protected function _beforeSave(AbstractModel $object)
    {
        return $this;
    }

    /**
     * Get FAQ record id.
     *
     * @param AbstractModel $object
     * @param mixed $value
     * @param string $field
     * @return bool|int|string
     * @throws LocalizedException
     * @throws \Exception
     */
    private function getFaqId(AbstractModel $object, $value, $field = null)
    {
        $entityMetadata = $this->metadataPool->getMetadata(FaqInterface::class);
        if (!is_numeric($value) && $field === null) {
            $field = 'faq_id';
        } elseif (!$field) {
            $field = $entityMetadata->getIdentifierField();
        }
        $entityId = $value;
        if ($field != $entityMetadata->getIdentifierField() || $object->getStoreId()) {
            $select = $this->_getLoadSelect($field, $value, $object);
            $select->reset(Select::COLUMNS)
                ->columns($this->getMainTable() . '.' . $entityMetadata->getIdentifierField())
                ->limit(1);
            $result = $this->getConnection()->fetchCol($select);
            $entityId = count($result) ? $result[0] : false;
        }
        return $entityId;
    }

    /**
     * Load an object
     *
     * @param \MageCloud\Faq\Model\Faq|AbstractModel $object
     * @param mixed $value
     * @param string $field field to load by (defaults to model id)
     * @return $this
     * @throws LocalizedException
     */
    public function load(AbstractModel $object, $value, $field = null)
    {
        $faqId = $this->getFaqId($object, $value, $field);
        if ($faqId) {
            $this->entityManager->load($object, $faqId);
        }
        return $this;
    }

    /**
     * Retrieve select object for load object data
     *
     * @param string $field
     * @param mixed $value
     * @param \MageCloud\Faq\Model\Faq|AbstractModel $object
     * @return Select
     * @throws LocalizedException
     */
    protected function _getLoadSelect($field, $value, $object)
    {
        $entityMetadata = $this->metadataPool->getMetadata(FaqInterface::class);
        $linkField = $entityMetadata->getLinkField();

        $select = parent::_getLoadSelect($field, $value, $object);

        if ($object->getStoreId()) {
            $stores = [(int)$object->getStoreId(), Store::DEFAULT_STORE_ID];

            $select->join(
                ['mfs' => $this->getTable('magecloud_faq_store')],
                $this->getMainTable() . '.' . $linkField . ' = mfs.' . $linkField,
                ['store_id']
            )
            ->where('is_active = ?', 1)
            ->where('mfs.store_id in (?)', $stores)
            ->order('store_id DESC')
            ->limit(1);
        }

        if ($object->getCategoryId()) {
            $categories = $object->getCategories();
            $select->join(
                ['mfcc' => $this->getTable('magecloud_faq_catalog_category')],
                $this->getMainTable() . '.' . $linkField . ' = mfcc.' . $linkField,
                ['category_id']
            )
            ->where('mfcc.category_id in (?)', $categories);
        }

        return $select;
    }

    /**
     * Get store ids to which specified item is assigned
     *
     * @param int $id
     * @return array
     * @throws LocalizedException
     */
    public function lookupStoreIds($id)
    {
        $connection = $this->getConnection();

        $entityMetadata = $this->metadataPool->getMetadata(FaqInterface::class);
        $linkField = $entityMetadata->getLinkField();

        $select = $connection->select()
            ->from(['mfs' => $this->getTable('magecloud_faq_store')], 'store_id')
            ->join(
                ['mf' => $this->getMainTable()],
                'mfs.' . $linkField . ' = mf.' . $linkField,
                []
            )
            ->where('mf.' . $entityMetadata->getIdentifierField() . ' = :fag_id');

        return $connection->fetchCol($select, ['fag_id' => (int)$id]);
    }

    /**
     * Get category ids to which specified item is assigned
     *
     * @param int $id
     * @return array
     * @throws LocalizedException
     */
    public function lookupCategoryIds($id)
    {
        $connection = $this->getConnection();

        $entityMetadata = $this->metadataPool->getMetadata(FaqInterface::class);
        $linkField = $entityMetadata->getLinkField();

        $select = $connection->select()
            ->from(['mfcc' => $this->getTable('magecloud_faq_catalog_category')], 'category_id')
            ->join(
                ['mf' => $this->getMainTable()],
                'mfcc.' . $linkField . ' = mf.' . $linkField,
                []
            )
            ->where('mf.' . $entityMetadata->getIdentifierField() . ' = :fag_id');

        return $connection->fetchCol($select, ['fag_id' => (int)$id]);
    }

    /**
     * Save an object.
     *
     * @param AbstractModel $object
     * @return $this
     * @throws \Exception
     */
    public function save(AbstractModel $object)
    {
        $this->entityManager->save($object);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function delete(AbstractModel $object)
    {
        $this->entityManager->delete($object);
        return $this;
    }
}
<?php
/**
 * @author andy
 * @email andyworkbase@gmail.com
 * @team MageCloud
 * @package MageCloud_Faq
 */
namespace MageCloud\Faq\Controller\Adminhtml\Faq\Modal;

use MageCloud\Faq\Controller\Adminhtml\Faq as FaqAction;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Backend\App\Action\Context;
use MageCloud\Faq\Api\FaqRepositoryInterface;
use MageCloud\Faq\Model\Faq;
use MageCloud\Faq\Model\FaqFactory;
use Magento\Store\Model\Store;
use Magento\Store\Model\StoreManager;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Controller\Result\Raw;
use Magento\Framework\Controller\Result\RawFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Registry;

/**
 * Class Save
 * @package MageCloud\Faq\Controller\Adminhtml\Faq\Modal
 */
class Save extends FaqAction implements HttpPostActionInterface
{
    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var FaqFactory
     */
    private $faqFactory;

    /**
     * @var FaqRepositoryInterface
     */
    private $faqRepository;

    /**
     * @var JsonFactory
     */
    private $resultJsonFactory;

    /**
     * @var RawFactory
     */
    private $resultRawFactory;

    /**
     * Save constructor.
     * @param Context $context
     * @param Registry $registry
     * @param StoreManager $storeManager
     * @param DataPersistorInterface $dataPersistor
     * @param FaqFactory $faqFactory
     * @param FaqRepositoryInterface $faqRepository
     * @param JsonFactory $resultJsonFactory
     * @param RawFactory $resultRawFactory
     */
    public function __construct(
        Context $context,
        Registry $registry,
        StoreManager $storeManager,
        DataPersistorInterface $dataPersistor,
        FaqFactory $faqFactory,
        FaqRepositoryInterface $faqRepository,
        JsonFactory $resultJsonFactory,
        RawFactory $resultRawFactory
    ) {
        parent::__construct($context, $registry, $storeManager);
        $this->dataPersistor = $dataPersistor;
        $this->faqFactory = $faqFactory;
        $this->faqRepository = $faqRepository;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->resultRawFactory = $resultRawFactory;
    }

    /**
     * Modal Save action
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @return Raw|Json
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function execute()
    {
        if (!$this->getRequest()->isAjax()) {
            /** @var Raw $resultRaw */
            $resultRaw = $this->resultRawFactory->create();
            return $resultRaw->setHttpResponseCode(400);
        }

        /** @var Json $resultJson */
        $resultJson = $this->resultJsonFactory->create();

        $response = [
            'errors' => false,
            'message' => __('Nothing to processing.')
        ];

        $data = $this->getRequest()->getPostValue();
        if ($data) {
            if (isset($data['is_active']) && $data['is_active'] === 'true') {
                $data['is_active'] = Faq::STATUS_ENABLED;
            }
            if (empty($data['store_id'])) {
                $data['store_id'] = Store::DEFAULT_STORE_ID;
            }
            if (empty($data['faq_id'])) {
                $data['faq_id'] = null;
            }

            /** @var \MageCloud\Faq\Model\Faq $model */
            $model = $this->faqFactory->create();

            $id = $this->getRequest()->getParam('faq_id');
            if ($id) {
                try {
                    $model = $this->faqRepository->getById($id);
                } catch (LocalizedException $e) {
                    $response = [
                        'errors' => true,
                        'message' => __('This record no longer exists.'),
                    ];
                    return $resultJson->setData($response);
                }
            }

            $model->setData($data);

            try {
                $model = $this->faqRepository->save($model);
                $response = [
                    'errors' => false,
                    'success' => true,
                    'data' => [
                        'faq_id' => $model->getId(),
                    ],
                    'message' => __('You saved the FAQ.'),
                ];
            } catch (LocalizedException $e) {
                $response = [
                    'errors' => true,
                    'message' => $e->getMessage(),
                ];

            } catch (\Exception $e) {
                $response = [
                    'errors' => true,
                    'message' => __('Something went wrong while saving the record.'),
                ];
            }
        }

        return $resultJson->setData($response);
    }
}

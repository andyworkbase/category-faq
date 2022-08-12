<?php
/**
 * @author andy
 * @email andyworkbase@gmail.com
 * @team MageCloud
 * @package MageCloud_Faq
 */
namespace MageCloud\Faq\Controller\Adminhtml\Faq;

use MageCloud\Faq\Controller\Adminhtml\Faq as FaqAction;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Backend\App\Action\Context;
use MageCloud\Faq\Api\FaqRepositoryInterface;
use MageCloud\Faq\Model\Faq;
use MageCloud\Faq\Model\FaqFactory;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Registry;
use Magento\Store\Model\StoreManager;

/**
 * Save CMS block action.
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
     * Save constructor.
     * @param Context $context
     * @param Registry $coreRegistry
     * @param StoreManager $storeManager
     * @param DataPersistorInterface $dataPersistor
     * @param FaqFactory|null $faqFactory
     * @param FaqRepositoryInterface|null $faqRepository
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        StoreManager $storeManager,
        DataPersistorInterface $dataPersistor,
        FaqFactory $faqFactory = null,
        FaqRepositoryInterface $faqRepository = null
    ) {
        $this->dataPersistor = $dataPersistor;
        $this->faqFactory = $faqFactory
            ?: \Magento\Framework\App\ObjectManager::getInstance()->get(FaqFactory::class);
        $this->faqRepository = $faqRepository
            ?: \Magento\Framework\App\ObjectManager::getInstance()->get(FaqRepositoryInterface::class);
        parent::__construct($context, $coreRegistry, $storeManager);
    }

    /**
     * Save action
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();
        if ($data) {
            if (isset($data['is_active']) && $data['is_active'] === 'true') {
                $data['is_active'] = Faq::STATUS_ENABLED;
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
                    $this->messageManager->addErrorMessage(__('This record no longer exists.'));
                    return $resultRedirect->setPath('*/*/');
                }
            }

            $model->setData($data);

            try {
                $model = $this->faqRepository->save($model);
                $this->messageManager->addSuccessMessage(__('You saved the record.'));
                $this->dataPersistor->clear('magecloud_faq');
                return $this->processBlockReturn($model, $data, $resultRedirect);
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the record.'));
            }

            $this->dataPersistor->set('magecloud_faq', $data);
            return $resultRedirect->setPath('*/*/edit', ['faq_id' => $id]);
        }
        return $resultRedirect->setPath('*/*/');
    }

    /**
     * Process and set the record return
     *
     * @param \MageCloud\Faq\Model\Faq $model
     * @param array $data
     * @param \Magento\Framework\Controller\ResultInterface $resultRedirect
     * @return \Magento\Framework\Controller\ResultInterface
     * @throws LocalizedException
     */
    private function processBlockReturn($model, $data, $resultRedirect)
    {
        if ($this->getRequest()->isAjax()) {
            return $resultRedirect->setRefererUrl();
        }

        $redirect = $data['back'] ?? 'close';

        if ($redirect === 'continue') {
            $resultRedirect->setPath('*/*/edit', ['faq_id' => $model->getId()]);
        } else if ($redirect === 'close') {
            $resultRedirect->setPath('*/*/');
        } else if ($redirect === 'duplicate') {
            $duplicateModel = $this->faqFactory->create(['data' => $data]);
            $duplicateModel->setId(null);
            $duplicateModel->setIsActive(Faq::STATUS_DISABLED);
            $this->faqRepository->save($duplicateModel);
            $id = $duplicateModel->getId();
            $this->messageManager->addSuccessMessage(__('You duplicated the record.'));
            $this->dataPersistor->set('magecloud_faq', $data);
            $resultRedirect->setPath('*/*/edit', ['faq_id' => $id]);
        }
        return $resultRedirect;
    }
}

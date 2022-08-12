<?php
/**
 * @author andy
 * @email andyworkbase@gmail.com
 * @team MageCloud
 * @package MageCloud_Faq
 */
namespace MageCloud\Faq\Controller\Adminhtml\Faq;

use Magento\Backend\App\Action\Context;
use MageCloud\Faq\Api\FaqRepositoryInterface as FaqRepository;
use Magento\Framework\Controller\Result\JsonFactory;
use MageCloud\Faq\Api\Data\FaqInterface;

/**
 * Class InlineEdit
 * @package MageCloud\Faq\Controller\Adminhtml\Faq
 */
class InlineEdit extends \Magento\Backend\App\Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'MageCloud_Faq::faq';

    /**
     * @var \MageCloud\Faq\Api\FaqRepositoryInterface
     */
    protected $faqRepository;

    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $jsonFactory;

    /**
     * InlineEdit constructor.
     * @param Context $context
     * @param FaqRepository $faqRepository
     * @param JsonFactory $jsonFactory
     */
    public function __construct(
        Context $context,
        FaqRepository $faqRepository,
        JsonFactory $jsonFactory
    ) {
        parent::__construct($context);
        $this->faqRepository = $faqRepository;
        $this->jsonFactory = $jsonFactory;
    }

    /**
     * @return \Magento\Framework\Controller\ResultInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute()
    {
        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->jsonFactory->create();
        $error = false;
        $messages = [];

        if ($this->getRequest()->getParam('isAjax')) {
            $postItems = $this->getRequest()->getParam('items', []);
            if (!count($postItems)) {
                $messages[] = __('Please correct the data sent.');
                $error = true;
            } else {
                foreach (array_keys($postItems) as $faqId) {
                    /** @var \MageCloud\Faq\Model\Faq $faq */
                    $faq = $this->faqRepository->getById($faqId);
                    try {
                        $faq->setData(array_merge($faq->getData(), $postItems[$faqId]));
                        $this->faqRepository->save($faq);
                    } catch (\Exception $e) {
                        $messages[] = $this->getErrorWithBlockId(
                            $faq,
                            __($e->getMessage())
                        );
                        $error = true;
                    }
                }
            }
        }

        return $resultJson->setData([
            'messages' => $messages,
            'error' => $error
        ]);
    }

    /**
     * Add faq title to error message
     *
     * @param FaqInterface $faq
     * @param string $errorText
     * @return string
     */
    protected function getErrorWithBlockId(FaqInterface $faq, $errorText)
    {
        return '[FAQ ID: ' . $faq->getId() . '] ' . $errorText;
    }
}

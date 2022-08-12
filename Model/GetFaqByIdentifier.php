<?php
/**
 * @author andy
 * @email andyworkbase@gmail.com
 * @team MageCloud
 * @package MageCloud_Faq
 */
namespace MageCloud\Faq\Model;

use MageCloud\Faq\Api\GetFaqByIdentifierInterface;
use MageCloud\Faq\Api\Data\FaqInterface;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Class GetFaqByIdentifier
 * @package MageCloud\Faq\Model
 */
class GetFaqByIdentifier implements GetFaqByIdentifierInterface
{
    /**
     * @var \MageCloud\Faq\Model\FaqFactory
     */
    private $faqFactory;

    /**
     * @var ResourceModel\Faq
     */
    private $faqResource;

    /**
     * GetFaqByIdentifier constructor.
     * @param FaqFactory $faqFactory
     * @param ResourceModel\Faq $faqResource
     */
    public function __construct(
        \MageCloud\Faq\Model\FaqFactory $faqFactory,
        \MageCloud\Faq\Model\ResourceModel\Faq $faqResource
    ) {
        $this->faqFactory = $faqFactory;
        $this->faqResource = $faqResource;
    }

    /**
     * @inheritdoc
     */
    public function execute(string $identifier, int $storeId) : FaqInterface
    {
        $faq = $this->faqFactory->create();
        $faq->setStoreId($storeId);
        $this->faqResource->load($faq, $identifier, FaqInterface::FAQ_ID);

        if (!$faq->getId()) {
            throw new NoSuchEntityException(__('The FAQ with the "%1" ID doesn\'t exist.', $identifier));
        }

        return $faq;
    }
}

<?php
/**
 * @author andy
 * @email andyworkbase@gmail.com
 * @team MageCloud
 * @package MageCloud_Faq
 */
namespace MageCloud\Faq\Block\Adminhtml\Faq\Edit;

use Magento\Backend\Block\Widget\Context;
use MageCloud\Faq\Api\FaqRepositoryInterface;
use Magento\Framework\Registry;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Class GenericButton
 * @package MageCloud\Faq\Block\Adminhtml\Block\Edit
 */
class GenericButton
{
    /**
     * @var Context
     */
    protected $context;

    /**
     * @var FaqRepositoryInterface
     */
    protected $faqRepository;

    /**
     * @var Registry
     */
    protected $registry;

    /**
     * GenericButton constructor.
     * @param Context $context
     * @param FaqRepositoryInterface $faqRepository
     * @param Registry $registry
     */
    public function __construct(
        Context $context,
        FaqRepositoryInterface $faqRepository,
        Registry $registry
    ) {
        $this->context = $context;
        $this->faqRepository = $faqRepository;
        $this->registry = $registry;
    }

    /**
     * Return FAQ record ID
     *
     * @return int|null
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getFaqId()
    {
        try {
            return $this->faqRepository->getById(
                $this->context->getRequest()->getParam('faq_id')
            )->getId();
        } catch (NoSuchEntityException $e) {

        }
        return null;
    }

    /**
     * Generate url by route and parameters
     *
     * @param   string $route
     * @param   array $params
     * @return  string
     */
    public function getUrl($route = '', $params = [])
    {
        return $this->context->getUrlBuilder()->getUrl($route, $params);
    }
}

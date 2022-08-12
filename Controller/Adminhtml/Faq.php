<?php
/**
 * @author andy
 * @email andyworkbase@gmail.com
 * @team MageCloud
 * @package MageCloud_Faq
 */
namespace MageCloud\Faq\Controller\Adminhtml;

use Magento\Framework\Registry;
use Magento\Store\Model\StoreManager;

/**
 * Class Faq
 * @package MageCloud\Faq\Controller\Adminhtml
 */
abstract class Faq extends \Magento\Backend\App\Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'MageCloud_Faq::faq';

    /**
     * @var Registry
     */
    protected $registry;

    /**
     * @var StoreManager
     */
    protected $storeManager;

    /**
     * Faq constructor.
     * @param \Magento\Backend\App\Action\Context $context
     * @param Registry $registry
     * @param StoreManager $storeManager
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        Registry $registry,
        StoreManager $storeManager
    ) {
        $this->registry = $registry;
        $this->storeManager = $storeManager;
        parent::__construct($context);
    }

    /**
     * Init page
     *
     * @param \Magento\Backend\Model\View\Result\Page $resultPage
     * @return \Magento\Backend\Model\View\Result\Page
     */
    protected function initPage($resultPage)
    {
        $resultPage->setActiveMenu('MageCloud_Faq::faq')
            ->addBreadcrumb(__('FAQ'), __('FAQ'))
            ->addBreadcrumb(__('Content'), __('Content'));
        return $resultPage;
    }
}

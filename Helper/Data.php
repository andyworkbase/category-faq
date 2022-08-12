<?php
/**
 * @author andy
 * @email andyworkbase@gmail.com
 * @team MageCloud
 * @package MageCloud_Faq
 */
namespace MageCloud\Faq\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\Locale\ResolverInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;

/**
 * Class Data
 * @package MageCloud\Faq\Helper
 */
class Data extends AbstractHelper
{
    /**
     * XML path
     */
    const XML_PATH_ENABLED = 'magecloud_faq/general/enabled';
    const XML_PATH_ENABLED_SCHEMA = 'magecloud_faq/general/enabled_schema';

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var ResolverInterface
     */
    private $localeResolver;

    /**
     * Data constructor.
     * @param \Magento\Framework\App\Helper\Context $context
     * @param StoreManagerInterface $storeManager
     * @param ResolverInterface $localeResolver
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        StoreManagerInterface $storeManager,
        ResolverInterface $localeResolver
    ) {
        parent::__construct($context);
        $this->storeManager = $storeManager;
        $this->scopeConfig = $context->getScopeConfig();
        $this->localeResolver = $localeResolver;
    }

    /**
     * @param null $store
     * @return \Magento\Store\Api\Data\StoreInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getStore($store = null)
    {
        return $this->storeManager->getStore($store);
    }

    /**
     * @param null $store
     * @return bool
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function isEnabled($store = null)
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_ENABLED,
            ScopeInterface::SCOPE_STORE,
            $store ?? $this->getStore()
        );
    }

    /**
     * @param null $store
     * @return bool
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function isEnabledSchema($store = null)
    {
        if ($this->isEnabled($store)) {
            return $this->scopeConfig->isSetFlag(
                self::XML_PATH_ENABLED_SCHEMA,
                ScopeInterface::SCOPE_STORE,
                $store ?? $this->getStore()
            );
        }
        return false;
    }

    /**
     * @return string
     */
    public function getLocale()
    {
        return $this->localeResolver->getLocale();
    }
}
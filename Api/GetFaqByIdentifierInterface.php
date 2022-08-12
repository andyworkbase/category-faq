<?php
/**
 * @author andy
 * @email andyworkbase@gmail.com
 * @team MageCloud
 * @package MageCloud_Faq
 */
namespace MageCloud\Faq\Api;

/**
 * Interface GetFaqByIdentifierInterface
 * @package MageCloud\Faq\Api
 */
interface GetFaqByIdentifierInterface
{
    /**
     * Load faq data by given faq identifier.
     *
     * @param string $identifier
     * @param int $storeId
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @return \MageCloud\Faq\Api\Data\FaqInterface
     * @since 103.0.0
     */
    public function execute(string $identifier, int $storeId) : \MageCloud\Faq\Api\Data\FaqInterface;
}

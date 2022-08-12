<?php
/**
 * @author andy
 * @email andyworkbase@gmail.com
 * @team MageCloud
 * @package MageCloud_Faq
 */
namespace MageCloud\Faq\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * Interface FaqSearchResultsInterface
 * @package MageCloud\Faq\Api\Data
 */
interface FaqSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get faqs list.
     *
     * @return \MageCloud\Faq\Api\Data\FaqInterface[]
     */
    public function getItems();

    /**
     * Set faqs list.
     *
     * @param \MageCloud\Faq\Api\Data\FaqInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}

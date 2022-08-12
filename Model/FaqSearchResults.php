<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace MageCloud\Faq\Model;

use MageCloud\Faq\Api\Data\FaqSearchResultsInterface;
use Magento\Framework\Api\SearchResults;

/**
 * Class FaqSearchResults
 * @package MageCloud\Faq\Model
 */
class FaqSearchResults extends SearchResults implements FaqSearchResultsInterface
{
}

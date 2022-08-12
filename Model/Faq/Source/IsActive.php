<?php
/**
 * @author andy
 * @email andyworkbase@gmail.com
 * @team MageCloud
 * @package MageCloud_Faq
 */
namespace MageCloud\Faq\Model\Faq\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class IsActive
 * @package MageCloud\Faq\Model\Faq\Source
 */
class IsActive implements OptionSourceInterface
{
    /**
     * @var \MageCloud\Faq\Model\Faq
     */
    protected $faq;

    /**
     * Constructor
     *
     * @param \MageCloud\Faq\Model\Faq $faq
     */
    public function __construct(\MageCloud\Faq\Model\Faq $faq)
    {
        $this->faq = $faq;
    }

    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        $availableOptions = $this->faq->getAvailableStatuses();
        $options = [];
        foreach ($availableOptions as $key => $value) {
            $options[] = [
                'label' => $value,
                'value' => $key,
            ];
        }
        return $options;
    }
}

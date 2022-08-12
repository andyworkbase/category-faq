<?php
/**
 * @author andy
 * @email andyworkbase@gmail.com
 * @team MageCloud
 * @package MageCloud_Faq
 */
namespace MageCloud\Faq\Ui\Component\Listing\Column;

use Magento\Ui\Component\Listing\Columns\Column;
use MageCloud\Faq\Model\Faq;

/**
 * Class Status
 * @package MageCloud\Faq\Ui\Component\Listing\Column
 */
class Status extends Column
{
    /**
     * @inheritDoc
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                $item[$this->getData('name')] = $this->prepareItem($item[$this->getData('name')]);
            }
        }
        return $dataSource;
    }

    /**
     * @param null $status
     * @return string
     */
    private function prepareItem($status = null)
    {
        $decoratorClassPath = 'minor';
        $title = __('Disabled');
        if ($status == Faq::STATUS_ENABLED) {
            $decoratorClassPath = 'notice';
            $title = __('Enabled');
        }
        return '<span class="grid-severity-' . $decoratorClassPath .'"><span>' . __($title) . '</span></span>';
    }
}

<?php
/**
 * @author andy
 * @email andyworkbase@gmail.com
 * @team MageCloud
 * @package MageCloud_Faq
 */
namespace MageCloud\Faq\Controller\Adminhtml\Faq;

use MageCloud\Faq\Controller\Adminhtml\Faq;
use Magento\Framework\App\Action\HttpPostActionInterface;

/**
 * Class Delete
 * @package Magento\Faq\Controller\Adminhtml\Faq
 */
class Delete extends Faq implements HttpPostActionInterface
{
    /**
     * Delete action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        // check if we know what should be deleted
        if ($id = $this->getRequest()->getParam('faq_id')) {
            try {
                // init model and delete
                $model = $this->_objectManager->create(\MageCloud\Faq\Model\Faq::class);
                $model->load($id);
                $model->delete();
                // display success message
                $this->messageManager->addSuccessMessage(__('You deleted the record.'));
                // go to grid
                return $resultRedirect->setRefererUrl();
            } catch (\Exception $e) {
                // display error message
                $this->messageManager->addErrorMessage($e->getMessage());
                // go back to edit form
                return $resultRedirect->setPath('*/*/edit', ['faq_id' => $id]);
            }
        }
        // display error message
        $this->messageManager->addErrorMessage(__('We can\'t find a record to delete.'));
        // go to grid
        return $resultRedirect->setRefererUrl();
    }
}

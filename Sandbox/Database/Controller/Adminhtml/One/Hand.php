<?php

namespace Sandbox\Database\Controller\Adminhtml\One;

use Magento\Backend\App\Action;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultFactory;

class Hand extends Action implements HttpGetActionInterface
{
    public function execute()
    {
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $resultPage->setActiveMenu('Sandbox_Database::Wanna')->addBreadcrumb(__('Powerbank'), __('List'));
        $resultPage->getConfig()->getTitle()->prepend(__('Powerbank'));

        return $resultPage;
    }
}

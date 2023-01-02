<?php

namespace Jo\Ker\Controller\Adminhtml\Laptop;

use Magento\Backend\App\Action;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultFactory;

class Index extends Action implements HttpGetActionInterface
{

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed("Jo_Ker::parent");
    }

    public function execute()
    {
        $page = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $page->setActiveMenu('Jo_Ker::joker')->addBreadcrumb(__('Laptops'), __('List'));
        $page->getConfig()->getTitle()->prepend(__('Laptops'));
        return $page;
    }
}

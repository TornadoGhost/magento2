<?php

namespace Jo\Ker\Controller\Adminhtml\Laptop;

use Jo\Ker\Api\LaptopRepositoryInterface;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultFactory;

class NewAction extends Action implements HttpGetActionInterface
{
    protected LaptopRepositoryInterface $laptopRepository;
    protected $resultFactory;

    public function __construct(Context $context,
                                ResultFactory $resultFactory,
                                LaptopRepositoryInterface $laptopRepository)
    {
        $this->resultFactory = $resultFactory;
        $this->laptopRepository = $laptopRepository;
        parent::__construct($context);
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed("Jo_Ker::parent");
    }

    public function execute()
    {
        $page = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $page->setActiveMenu('Jo_Ker::joker')->addBreadcrumb(__('New Laptop'), __('Laptop'));
        $page->getConfig()->getTitle()->prepend(__('New Laptop'));

        return $page;
    }
}

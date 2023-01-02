<?php

namespace Jo\Ker\Controller\Adminhtml\Laptop;

use Jo\Ker\Api\LaptopRepositoryInterface;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\NoSuchEntityException;

class Delete extends Action implements HttpPostActionInterface
{
    private LaptopRepositoryInterface $laptopRepository;

    public function __construct(Context $context, LaptopRepositoryInterface $laptopRepository)
    {
        $this->laptopRepository = $laptopRepository;
        parent::__construct($context);
    }

    public function execute()
    {
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $request = $this->getRequest();
        $laptopId = $request->getParam('id');

        if(!$laptopId){
            $this->messageManager->addErrorMessage(__('Error.'));
            return $resultRedirect->setPath('*/*/index');
        }

        try {
            $laptop = $this->laptopRepository->getById($laptopId);
            $this->laptopRepository->delete($laptop);
            $this->messageManager->addSuccessMessage(__('You have successfully deleted the laptop'));
        } catch (NoSuchEntityException $e){
            $this->messageManager->addErrorMessage(__('Cannot delete the laptop'));
        }

        return $resultRedirect->setPath('*/*/index');
    }
}

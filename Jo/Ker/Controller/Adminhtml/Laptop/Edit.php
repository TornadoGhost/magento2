<?php

namespace Jo\Ker\Controller\Adminhtml\Laptop;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Jo\Ker\Model\LaptopFactory;
use Jo\Ker\Model\LaptopRepository;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\UrlInterface;

class Edit extends Action implements HttpGetActionInterface
{
    protected $resultFactory;
    private LaptopRepository $laptopRepository;

    public function __construct(Context          $context,
                                ResultFactory    $resultFactory,
                                LaptopRepository $laptopRepository
    )
    {
        $this->laptopRepository = $laptopRepository;
        $this->resultFactory = $resultFactory;
        parent::__construct($context);
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed("Jo_Ker::parent");
    }

    public function execute()
    {
        /*$page = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $page->getConfig()->getTitle()->prepend(__('Laptops Form'));
        return $page;*/

        $id = $this->getRequest()->getParam('id');
        try {
            $laptop = $this->laptopRepository->getById($id);

            $result = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
            $result->setActiveMenu('Jo_Ker::joker')->addBreadcrumb(__('Edit Laptop'), __('Laptop'));
            $result->getConfig()->getTitle()->prepend(__('Edit Laptop: %name', ['name' => $laptop->getName()]));
        } catch (NoSuchEntityException $e){
            $result = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
            $this->messageManager->addErrorMessage(
                __('Laptop with id "%value" does not exist.', ['value' => $id])
            );
            $result->setPath('*/*');
        }
        return $result;
    }
}

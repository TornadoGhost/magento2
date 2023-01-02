<?php

namespace Jo\Ker\Controller\Adminhtml\Laptop;

use Jo\Ker\Api\LaptopRepositoryInterface;
use Jo\Ker\Model\LaptopFactory;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Jo\Ker\Api\Data\LaptopInterface;
use Magento\Framework\Controller\Result\Redirect;

class Save extends Action implements HttpPostActionInterface
{
    protected LaptopRepositoryInterface $laptopRepository;
    protected LaptopFactory $laptopFactory;

    public function __construct(Context                   $context,
                                LaptopRepositoryInterface $laptopRepository,
                                LaptopFactory             $laptopFactory)
    {
        $this->laptopRepository = $laptopRepository;
        $this->laptopFactory = $laptopFactory;
        parent::__construct($context);
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed("Jo_Ker::parent");
    }

    public function execute()
    {
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $request = $this->getRequest();
        $requestData = $request->getParams();

        if (!$requestData || empty($requestData['general'])) {
            $this->messageManager->addErrorMessage(__('Wrong request.'));
            $resultRedirect->setPath('*/*/new');
            return $resultRedirect;
        }

        try {
            $id = $requestData['general'][LaptopInterface::LAPTOP_ID];
            $laptop = $this->laptopRepository->getById($id);
        } catch (\Exception $e) {
            $laptop = $this->laptopFactory->create();
        }

        $laptop->setName($requestData['general'][LaptopInterface::NAME]);
        $laptop->setScreen($requestData['general'][LaptopInterface::SCREEN]);
        $laptop->setProcessor($requestData['general'][LaptopInterface::PROCESSOR]);
        $laptop->setRam($requestData['general'][LaptopInterface::RAM]);
        $laptop->setDrive($requestData['general'][LaptopInterface::DRIVE]);

        try {
            $laptop = $this->laptopRepository->save($laptop);
            $this->processRedirectAfterSuccessSave($resultRedirect, $laptop->getId());
            $this->messageManager->addSuccessMessage(__("Laptop's data was saved."));
        } catch (\Exception $exception) {
            $this->messageManager->addErrorMessage(__('Error. Cannot save'));

            $resultRedirect->setPath('*/*/new');
        }

        return $resultRedirect->setPath('*/*/index');
    }

    private function processRedirectAfterSuccessSave(Redirect $resultRedirect, string $id)
    {
        if ($this->getRequest()->getParam('back')) {
            $resultRedirect->setPath(
                '*/*/edit',
                [
                    LaptopInterface::LAPTOP_ID => $id,
                    '_current' => true,
                ]
            );
        } elseif ($this->getRequest()->getParam('redirect_to_new')) {
            $resultRedirect->setPath(
                '*/*/new',
                [
                    '_current' => true,
                ]
            );
        } else {
            $resultRedirect->setPath('*/*/');
        }
    }

}

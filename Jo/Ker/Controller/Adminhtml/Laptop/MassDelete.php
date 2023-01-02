<?php

namespace Jo\Ker\Controller\Adminhtml\Laptop;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Redirect;
use Jo\Ker\Model\ResourceModel\Laptop\CollectionFactory;
use Jo\Ker\Api\LaptopRepositoryInterface;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\NotFoundException;
use Magento\Ui\Component\MassAction\Filter;

class MassDelete extends Action implements HttpPostActionInterface
{
    protected $collectionFactory;


    private $laptopRepository;

    protected $filter;

    public function __construct(Context                   $context,
                                Filter                    $filter,
                                CollectionFactory         $collectionFactory,
                                LaptopRepositoryInterface $laptopRepository)
    {
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        $this->laptopRepository = $laptopRepository;
        parent::__construct($context);
    }

    public function execute(): Redirect
    {
        if(!$this->getRequest()->isPost()){
            throw new NotFoundException(__('Page not found'));
        }

        $collection = $this->filter->getCollection($this->collectionFactory->create());
        $laptopDeleted = 0;
        foreach ($collection->getItems() as $laptop){
            $this->laptopRepository->delete($laptop);
            $laptopDeleted++;
        }
        if($laptopDeleted) {
            $this->messageManager->addSuccessMessage(
                __('A total of %1 record(s) have been deleted.', $laptopDeleted)
            );
        }

        return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setPath('joker/laptop/index');
    }
}

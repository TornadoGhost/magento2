<?php

namespace Sandbox\Database\Model;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Sandbox\Database\Api\Data\PowerbankInterface;
use Sandbox\Database\Api\PowerbankRepositoryInterface;
use Sandbox\Database\Model\ResourceModel\Powerbank;
use Sandbox\Database\Model\PowerbankFactory;
use Sandbox\Database\Model\ResourceModel\Powerbank\CollectionFactory;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Sandbox\Database\Api\Data\PowerbankSearchResultInterfaceFactory;

class PowerbankRepository implements PowerbankRepositoryInterface
{
    protected $resourceModel;
    protected $factory;
    protected $collectionFactory;
    protected $collectionProcessor;
    protected $searchResultFactory;

    public function __construct(Powerbank                             $resourceModel,
                                PowerbankFactory                      $factory,
                                CollectionFactory                     $collectionFactory,
                                CollectionProcessorInterface          $collectionProcessor,
                                PowerbankSearchResultInterfaceFactory $searchResultFactory,

    )
    {
        $this->factory = $factory;
        $this->resourceModel = $resourceModel;
        $this->collectionFactory = $collectionFactory;
        $this->collectionProcessor = $collectionProcessor;
        $this->searchResultFactory = $searchResultFactory;
    }

    public function getById(int $id)
    {
        $powerbank = $this->factory->create();
        $this->resourceModel->load($powerbank, $id);
        if (!$powerbank->getId()) {
            throw new NoSuchEntityException();
        }
        return $powerbank;
    }

    public function save(PowerbankInterface $powerbank)
    {
        try {
            $this->resourceModel->save($powerbank);
        } catch (\Exception $e) {
            throw new CouldNotSaveException(__($e->getMessage()));
        }
        return $powerbank;
    }

    public function delete(PowerbankInterface $powerbank)
    {
        try {
            $this->resourceModel->delete($powerbank);
        } catch (\Exception $e) {
            throw new CouldNotDeleteException(__('Could not delete the entry: %1', $e->getMessage()));
        }
        return true;
    }

    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        $collection = $this->collectionFactory->create();
        $this->collectionProcessor->process($searchCriteria, $collection);
        $searchResult = $this->searchResultFactory->create();

        $searchResult->setSearchCriteria($searchCriteria);
        $searchResult->setItems($collection->getItems());

        return $searchResult;
    }
}

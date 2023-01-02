<?php

namespace Jo\Ker\Model;

use Jo\Ker\Api\Data\LaptopInterface;
use Jo\Ker\Api\Data\LaptopSearchResultInterfaceFactory;
use Jo\Ker\Model\ResourceModel\Laptop\CollectionFactory;
use Jo\Ker\Api\LaptopRepositoryInterface;
use Jo\Ker\Model\ResourceModel\Laptop as ResourceModel;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;




class LaptopRepository implements LaptopRepositoryInterface
{
    protected $laptopFactory;
    protected $laptopResource;
    protected $laptopCollectionFactory;
    protected $collectionProcessor;
    protected $searchResultFactory;

    public function __construct(
        LaptopFactory $laptopFactory,
        ResourceModel  $laptopResource,
        CollectionFactory $laptopCollectionFactory,
        CollectionProcessorInterface $collectionProcessor,
        LaptopSearchResultInterfaceFactory $searchResultFactory,
    )
    {
        $this->laptopFactory = $laptopFactory;
        $this->laptopResource = $laptopResource;
        $this->laptopCollectionFactory = $laptopCollectionFactory;
        $this->collectionProcessor = $collectionProcessor;
        $this->searchResultFactory = $searchResultFactory;
    }

    public function getById($id)
    {
        $laptop = $this->laptopFactory->create();
        $this->laptopResource->load($laptop, $id);
        if(!$laptop->getId()){
            throw new NoSuchEntityException(__('Unable to find book with ID "%1"',$id));
        }
        return $laptop;
    }

    public function save(LaptopInterface $laptop)
    {
        try {
            $this->laptopResource->save($laptop);
        } catch (\Exception $e){
            throw new CouldNotSaveException(__($e->getMessage()));
        }
        return $laptop;
    }

    public function delete(LaptopInterface $laptop)
    {
        try {
            $this->laptopResource->delete($laptop);
        } catch (\Exception $exception){
            throw new CouldNotDeleteException(__('Could not delete the entry: %1', $exception->getMessage()));
        }
        return true;
    }

    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        $collection = $this->laptopCollectionFactory->create();
        $this->collectionProcessor->process($searchCriteria, $collection);
        $searchResults = $this->searchResultFactory->create();

        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());
        return $searchResults;
    }
}

<?php

namespace Bohdan\HelloWorld\Model;

use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

use Bohdan\HelloWorld\Api\Data\BookInterface;
use Bohdan\HelloWorld\Api\Data\BookSearchResultInterfaceFactory;
use Bohdan\HelloWorld\Api\BookRepositoryInterface;
use Bohdan\HelloWorld\Model\ResourceModel\Book;
use Bohdan\HelloWorld\Model\ResourceModel\Book\CollectionFactory;

class BookRepository implements BookRepositoryInterface
{
    private $bookFactory;
    private $bookResource;
    private $bookCollectionFactory;
    private $collectionProcessor;
    protected $searchResultFactory;

    public function __construct(
        BookFactory $bookFactory,
        Book $bookResource,
        CollectionFactory $bookCollectionFactory,
        CollectionProcessorInterface $collectionProcessor,
        BookSearchResultInterfaceFactory $searchResultFactory,

    ) {
        $this->bookFactory = $bookFactory;
        $this->bookResource = $bookResource;
        $this->bookCollectionFactory = $bookCollectionFactory;
        $this->collectionProcessor = $collectionProcessor;
        $this->searchResultFactory = $searchResultFactory;
    }

    public function getById($id)
    {
        $book = $this->bookFactory->create();
        $this->bookResource->load($book, $id);
        if (!$book->getId()) {
            throw new NoSuchEntityException(__('Unable to find Book with ID "%1"', $id));
        }
        return $book;
    }

    public function save(BookInterface $book)
    {
        try {
            $this->bookResource->save($book);
        } catch (\Exception $e) {
            throw new CouldNotSaveException(__($e->getMessage()));
        }
        return $book;
    }

    public function delete(BookInterface $book)
    {
        try {
            $this->bookResource->delete($book);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__('Could not delete the entry: %1', $exception->getMessage()));
        }
        return true;
    }

    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        $collection = $this->bookCollectionFactory->create()->testLeftJoin();
        $this->collectionProcessor->process($searchCriteria, $collection);
        $searchResults = $this->searchResultFactory->create();

        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());

        /*echo "</br>";
        echo $collection->getSelect()->__toString();
        echo "</br>";*/

        return $searchResults;
    }
}

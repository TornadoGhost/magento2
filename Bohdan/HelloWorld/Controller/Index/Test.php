<?php

namespace Bohdan\HelloWorld\Controller\Index;

use http\Client;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Cache\Backend\MongoDb;
use Magento\Framework\Controller\ResultFactory;


class Test implements HttpGetActionInterface
{
    protected $resultFactory;
    protected $book;
    protected $bookCollection;
    protected $bookRepository;
    protected $filterBuilder;
    protected $searchCriteriaBuilder;

    public function __construct(ResultFactory                                                 $resultFactory,
                                \Bohdan\HelloWorld\Model\BookFactory                          $book,
                                \Bohdan\HelloWorld\Model\ResourceModel\Book\CollectionFactory $bookCollection,
                                \Bohdan\HelloWorld\Model\BookRepository                       $bookRepository,
                                \Magento\Framework\Api\FilterBuilder                          $filterBuilder,
                                \Magento\Framework\Api\Search\SearchCriteriaBuilder           $searchCriteriaBuilder)
    {
        $this->book = $book;
        $this->resultFactory = $resultFactory;
        $this->bookCollection = $bookCollection;
        $this->bookRepository = $bookRepository;
        $this->filterBuilder = $filterBuilder;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    public function execute()
    {
        #Insert Operation using Repository
        /*$book = $this->book->create();
        $book->setTitle('Title test');
        $book->setDescription('Desc test');
        $book->setAuthorId('1');
        $book->setPublisherId('1');
        $this->bookRepository->save($book);*/

        #Load entity using Repository
        /*$book = $this->bookRepository->getById(9);
        print_r($book->getData());*/

        #Update Operation using Repository
        /*$book = $this->bookRepository->getById(10);
        $book->setTitle('Changed title');
        $this->bookRepository->save($book);*/

        #Delete Operation using Repository
        /*$book = $this->bookRepository->getById(10);
        $this->bookRepository->delete($book);*/

        /*$filter = $this->filterBuilder->setField('title')
            ->setConditionType('like')
            ->setValue('%title%')
            ->create();
        $this->searchCriteriaBuilder->addFilter($filter);
        $searchCriteria = $this->searchCriteriaBuilder->create();
        $searchResult = $this->bookRepository->getList($searchCriteria);
        $items = $searchResult->getItems();
        echo "Number of record== " . count($items);
        echo "</br>";
        foreach ($items as $item) {
            echo "</br>";
            echo "ID :" . $item->getId() . " ". "Title : ". $item->getTitle() . " Description : " . $item->getDescription() . " Author Name: " . $item->getLastName();
            echo "</br>";
        }*/


       return $this->resultFactory->create(ResultFactory::TYPE_PAGE);
    }
}

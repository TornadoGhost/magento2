<?php

namespace Bohdan\HelloWorld\Block;

use Bohdan\HelloWorld\Model\ResourceModel\Book\Collection;
use Magento\Framework\View\Element\Template;

class Display extends \Magento\Framework\View\Element\Template
{
    protected $collection;
    protected $authorCollection;
    protected $publisherCollection;
    protected $book;
    protected $book1;

    public function __construct(Template\Context                                            $context,
                                Collection                                                  $collection,
                                \Bohdan\HelloWorld\Model\ResourceModel\Author\Collection    $authorCollection,
                                \Bohdan\HelloWorld\Model\ResourceModel\Publisher\Collection $publisherCollection,
                                \Bohdan\HelloWorld\Model\ResourceModel\Book                 $book,
                                \Bohdan\HelloWorld\Model\Book                               $book1,
                                array                                                       $data = [])
    {
        parent::__construct($context, $data);
        $this->collection = $collection;
        $this->authorCollection = $authorCollection;
        $this->publisherCollection = $publisherCollection;
        $this->book = $book;
        $this->book1 = $book1;
    }

    public function getFormAction()
    {
        return $this->getUrl('extension/index/submit', ['_secure' => true]);
    }

    public function sayHello()
    {
        return _('Hello, its main/index/index');
    }

    public function message()
    {
        return 'Hello, Its main/index/page';
    }

    public function getAllBooks()
    {
        return $this->collection;
    }

    public function getAllAuthors()
    {
        return $this->authorCollection;
    }

    public function getAllPublishers()
    {
        return $this->publisherCollection;
    }

    public function getCol()
    {
        return $this->collection->testLeftJoin();
    }

    public function getBook()
    {
        /*$title = $this->getData('title');
        $obj = $this->book1;
        $this->book->load($obj, $title, 'title');
        return $this;*/
    }

    public function getBookTitle()
    {
        $this->collection->fetchAll();
        $title = $this->getData('title');
        $authorTable = $this->collection->getTable('author');
        $data = $this->collection->getSelect()->joinLeft([$authorTable], "main_table.author_id = author.author_id");
        return $data;
    }
}

//["author" => $this->collection->getTable("author")],
//'book.author_id = author.author_id'
/*    public function getJoinLeft(){
        $authorTable = $this->getTable('author');
        return $this->collection->getSelect()->joinLeft(
            array("author" => $authorTable),
            "book.author_id = author.author_id"
        );
    }*/

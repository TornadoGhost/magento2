<?php

namespace Bohdan\HelloWorld\Controller\Index;

use Bohdan\HelloWorld\Model\Book;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\Request\Http;
use Magento\Framework\Controller\ResultFactory;

class Get implements HttpGetActionInterface
{
    protected $book;
    protected $resultFactory;
    protected $request;

    public function __construct(Book $book, ResultFactory $resultFactory, Http $request)
    {
        $this->book = $book;
        $this->resultFactory = $resultFactory;
        $this->request = $request;
    }

    public function execute()
    {
        return $this->resultFactory->create(ResultFactory::TYPE_PAGE);
    }
}

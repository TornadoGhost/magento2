<?php

namespace Bohdan\HelloWorld\Controller\Index;


use Bohdan\HelloWorld\Model\ResourceModel\Book\Collection;
use Magento\Checkout\Block\Success;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\App\Request\Http as Request;
use Magento\Framework\Message\ManagerInterface;

class Save implements HttpPostActionInterface
{
    protected $resultFactory;
    protected $request;
    protected $book;
    protected $messageManager;

    public function __construct(ResultFactory $resultFactory, Request $request, Collection $book, ManagerInterface $messageManager)
    {
        $this->resultFactory = $resultFactory;
        $this->request = $request;
        $this->book = $book;
        $this->messageManager = $messageManager;
    }

    public function execute()
    {
        /*$data = $this->request->getPost();
        $freshData = [];
        foreach ($data as $k => $v){
            if($k == 'form_key'){
                break;
            }
            $freshData[$k] = $v;
        }

        $connect = $this->book->getConnection();
        $connect->insert('book', $freshData);
        $this->messageManager->addSuccessMessage(__("Success"));*/
        return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setUrl('/main/index/test');
    }
}

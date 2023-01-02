<?php

namespace Bohdan\HelloWorld\Controller\Index;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\Request\Http;
use Magento\Framework\Controller\ResultFactory;

class Show implements HttpGetActionInterface
{
    protected $resultFactory;
    protected $request;

    public function __construct(ResultFactory $resultFactory, Http $request)
    {
        $this->resultFactory = $resultFactory;
        $this->request = $request;
    }

    public function execute()
    {
        if($this->request->getParams()){
            $parametres = $this->request->getParams();
        }
        $page = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $block = $page->getLayout()->getBlock('bohdan.helloworld.index.show');
        $block->setData('title', $parametres['title']);
        return $page;
    }
}

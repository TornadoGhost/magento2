<?php
declare(strict_types=1);
namespace Bohdan\HelloWorld\Controller;
use Magento\Framework\App\Action\Forward;
use Magento\Framework\App\ActionFactory;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\App\RouterInterface;
/**
 * Class Router
 */
class Router implements RouterInterface
{
    /**
     * @var \Magento\Framework\App\ActionFactory
     */
    protected $actionFactory;

    /**
     * Response
     *
     * @var \Magento\Framework\App\ResponseInterface
     */
    protected $_response;

    /**
     * @param \Magento\Framework\App\ActionFactory $actionFactory
     * @param \Magento\Framework\App\ResponseInterface $response
     */
    public function __construct(\Magento\Framework\App\ActionFactory $actionFactory, \Magento\Framework\App\ResponseInterface $response)
    {
        $this->actionFactory = $actionFactory;
        $this->_response = $response;
    }

    /**
     * @param \Magento\Framework\App\RequestInterface $request
     * @return \Magento\Framework\App\ActionInterface|void
     */
    public function match(\Magento\Framework\App\RequestInterface $request)
    {
        $identifier = trim($request->getPathInfo() , '/');
        if (strpos($identifier, 'learning') !== false)
        {
            $request->setModuleName('main')
                ->setControllerName('index')
                ->setActionName('index')
                ->setParam('page_id', 6);
        }
        else if (strpos($identifier, 'page') !== false)
        {
            $request->setModuleName('main')
                ->setControllerName('index')
                ->setActionName('page');
        }
        else
        {
            return;
        }

        return $this
            ->actionFactory
            ->create('Magento\Framework\App\Action\Forward', ['request' => $request]);
    }
}

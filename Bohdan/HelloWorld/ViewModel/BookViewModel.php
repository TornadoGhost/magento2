<?php

namespace Bohdan\HelloWorld\ViewModel;

use Bohdan\HelloWorld\Api\BookRepositoryInterface;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\SearchCriteriaBuilder;
use Magento\Framework\App\Request\Http;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class BookViewModel implements ArgumentInterface
{
    protected $bookRepository;
    protected $searchCriteriaBuilder;
    protected $filterBuilder;
    protected $request;



    public function __construct(BookRepositoryInterface $bookRepository,
                                SearchCriteriaBuilder   $searchCriteriaBuilder,
                                FilterBuilder           $filterBuilder,
                                Http                    $request)
    {
        $this->bookRepository = $bookRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->filterBuilder = $filterBuilder;
        $this->request = $request;
    }

    public function tryIndex()
    {

    }

    public function info()
    {
        phpinfo();
        var_dump(phpinfo());
    }

    public function getParams()
    {
        return $this->request->getParams();
    }

    public function getList()
    {
        $filter = $this->filterBuilder->setField('title')
            ->setConditionType('like')
            ->setValue('%title%')
            ->create();
        $this->searchCriteriaBuilder->addFilter($filter);
        $searchCriteria = $this->searchCriteriaBuilder->create();
        $searchResult = $this->bookRepository->getList($searchCriteria);
        return $searchResult->getItems();
    }
}

<?php

namespace Bohdan\HelloWorld\Model;

use Magento\Framework\Api\SearchResults;
use Bohdan\HelloWorld\Api\Data\BookSearchResultInterface;
class BookSearchResult extends SearchResults implements BookSearchResultInterface
{

    public function getAggregations()
    {
        // TODO: Implement getAggregations() method.
    }

    public function setAggregations($aggregations)
    {
        // TODO: Implement setAggregations() method.
    }
}

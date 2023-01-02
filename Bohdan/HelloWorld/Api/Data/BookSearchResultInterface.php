<?php

namespace Bohdan\HelloWorld\Api\Data;

use Magento\Framework\Api\Search\SearchResultInterface;

interface BookSearchResultInterface extends SearchResultInterface
{
    /**
     * @return \Bohdan\HelloWorld\Api\Data\BookInterface[]
     */
    public function getItems();

    /**
     * @param \Bohdan\HelloWorld\Api\Data\BookInterface[] $items
     * @return $this
     */
    public function setItems(array $items = null);
}

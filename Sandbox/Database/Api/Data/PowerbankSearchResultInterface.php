<?php

namespace Sandbox\Database\Api\Data;

use Magento\Framework\Api\Search\SearchResultInterface;

interface PowerbankSearchResultInterface extends SearchResultInterface
{
    public function getItems();

    public function setItems(array $items = null);
}

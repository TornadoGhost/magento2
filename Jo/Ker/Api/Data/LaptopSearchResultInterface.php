<?php

namespace Jo\Ker\Api\Data;

use Magento\Framework\Data\SearchResultInterface;
use Magento\Framework\DataObject;

interface LaptopSearchResultInterface extends SearchResultInterface
{
    /**
     * @return LaptopInterface[]
     */
    public function getItems();

    /**
     * @param LaptopInterface[] $items
     * @return void
     */
    public function setItems(array $items);
}

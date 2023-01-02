<?php

namespace Jo\Ker\Model;

use Magento\Ui\DataProvider\AbstractDataProvider;
use Jo\Ker\Model\ResourceModel\Laptop\CollectionFactory;

class DataProvider extends AbstractDataProvider
{
    public function __construct(CollectionFactory $collectionFactory,
                                                  $name,
                                                  $primaryFieldName,
                                                  $requestFieldName,
                                array             $meta = [],
                                array             $data = [])
    {
        $this->collection = $collectionFactory->create();
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    public function getDataSourseData(): array
    {
        return [];
    }


    public function getData()
    {
        return parent::getData();
    }

    public function getMeta(): array
    {
        return $this->meta;
    }
}

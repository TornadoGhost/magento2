<?php

namespace Jo\Ker\Model\ResourceModel\Laptop;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Jo\Ker\Model\Laptop as Model;
use Jo\Ker\Model\ResourceModel\Laptop as ResourceModel;

class Collection extends AbstractCollection
{
    protected $_eventPrefix = 'laptop_collection';

    protected function _construct()
    {
        $this->_init(Model::class, ResourceModel::class);
    }

    protected function _beforeLoad()
    {
        return parent::_beforeLoad(); // TODO: Change the autogenerated stub
    }
}

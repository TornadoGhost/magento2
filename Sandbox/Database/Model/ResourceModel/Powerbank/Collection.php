<?php

namespace Sandbox\Database\Model\ResourceModel\Powerbank;

use Sandbox\Database\Model\Powerbank as Model;
use Sandbox\Database\Model\ResourceModel\Powerbank as ResourceModel;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    public function _construct()
    {
        $this->_init(Model::class, ResourceModel::class);
    }
}

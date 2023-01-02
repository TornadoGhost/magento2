<?php

namespace Jo\Ker\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Laptop extends AbstractDb
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'laptop_resource_model';

    protected function _construct()
    {
        $this->_init('laptop', 'laptop_id');
    }
}

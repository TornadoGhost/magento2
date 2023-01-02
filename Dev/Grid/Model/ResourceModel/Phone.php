<?php

namespace Dev\Grid\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Phone extends AbstractDb
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'phone_resource_model';

    protected function _construct()
    {
        $this->_init('phone', 'phone_id');
    }
}

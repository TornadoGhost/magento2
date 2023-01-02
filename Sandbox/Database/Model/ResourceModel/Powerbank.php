<?php

namespace Sandbox\Database\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Powerbank extends AbstractDb
{

    protected function _construct()
    {
        $this->_init('powerbank', 'powerbank_id');
    }
}

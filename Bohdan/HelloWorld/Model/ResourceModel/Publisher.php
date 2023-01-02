<?php

namespace Bohdan\HelloWorld\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Publisher extends AbstractDb
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'publisher_resource_model';

    /**
     * Initialize resource model.
     */
    protected function _construct()
    {
        $this->_init('publisher', 'publisher_id');
        $this->_useIsObjectNew = true;
    }
}

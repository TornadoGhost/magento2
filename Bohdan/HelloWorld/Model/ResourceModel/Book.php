<?php

namespace Bohdan\HelloWorld\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Book extends AbstractDb
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'book_resource_model';

    /**
     * Initialize resource model.
     */
    protected function _construct()
    {
        $this->_init('book', 'book_id');
    }

}

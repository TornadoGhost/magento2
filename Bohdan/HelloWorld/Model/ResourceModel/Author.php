<?php

namespace Bohdan\HelloWorld\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Author extends AbstractDb
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'author_resource_model';

    /**
     * Initialize resource model.
     */
    protected function _construct()
    {
        $this->_init('author', 'author_id');
        $this->_useIsObjectNew = true;
    }
}

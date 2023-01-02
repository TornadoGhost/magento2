<?php

namespace Bohdan\HelloWorld\Model\ResourceModel\Publisher;

use Bohdan\HelloWorld\Model\Publisher as Model;
use Bohdan\HelloWorld\Model\ResourceModel\Publisher as ResourceModel;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'publisher_collection';

    /**
     * Initialize collection model.
     */
    protected function _construct()
    {
        $this->_init(Model::class, ResourceModel::class);
    }
}

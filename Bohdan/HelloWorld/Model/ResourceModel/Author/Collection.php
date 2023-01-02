<?php

namespace Bohdan\HelloWorld\Model\ResourceModel\Author;

use Bohdan\HelloWorld\Model\Author as Model;
use Bohdan\HelloWorld\Model\ResourceModel\Author as ResourceModel;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'author_collection';

    /**
     * Initialize collection model.
     */
    protected function _construct()
    {
        $this->_init(Model::class, ResourceModel::class);
    }
}

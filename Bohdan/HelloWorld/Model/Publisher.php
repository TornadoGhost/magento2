<?php

namespace Bohdan\HelloWorld\Model;

use Bohdan\HelloWorld\Model\ResourceModel\Publisher as ResourceModel;
use Magento\Framework\Model\AbstractModel;

class Publisher extends AbstractModel
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'publisher_model';

    /**
     * Initialize magento model.
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(ResourceModel::class);
    }
}

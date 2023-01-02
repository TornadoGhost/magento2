<?php

namespace Dev\Grid\Model;

use Magento\Framework\Model\AbstractExtensibleModel;
use Dev\Grid\Model\ResourceModel\Phone as ResourceModel;

class Phone extends AbstractExtensibleModel
{
    const CACHE_TAG = 'phone';
    /**
     * @var string
     */
    protected $_eventPrefix = 'phone_model';

    protected function _construct()
    {
        $this->_init(ResourceModel::class);
    }
}

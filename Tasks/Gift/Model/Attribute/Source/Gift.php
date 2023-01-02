<?php

namespace Tasks\Gift\Model\Attribute\Source;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

class Gift extends AbstractSource
{

    public function getAllOptions()
    {
            $this->_options = [
                ['label' => __('None'), 'value' => NULL],
                ['label' => __('Kelvin Slay'), 'value' => 'MySkuPants'],
                ['label' => __('Dead Bag'), 'value' => 'dead-bag']
            ];
        return $this->_options;
    }
}

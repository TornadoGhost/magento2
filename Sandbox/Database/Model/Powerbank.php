<?php

namespace Sandbox\Database\Model;

use Magento\Framework\Model\AbstractModel;
use Sandbox\Database\Api\Data\PowerbankInterface;

class Powerbank extends AbstractModel implements PowerbankInterface
{

    /**
     * @return int|void
     */
    public function getPowerbankId()
    {
        parent::getData(self::POWERBANK_ID);
    }

    /**
     * @return string|void
     */
    public function getName()
    {
        parent::getData(self::NAME);
    }

    /**
     * @param $name
     * @return string|void
     */
    public function setName($name)
    {
        $this->setData(self::NAME, $name);
    }

    /**
     * @return int|void
     */
    public function getCapacity()
    {
        parent::getData(self::CAPACITY);
    }

    /**
     * @param $capacity
     * @return int|void
     */
    public function setCapacity($capacity)
    {
        $this->setData(self::CAPACITY, $capacity);
    }

    /**
     * @return int|void
     */
    public function getWeight()
    {
        parent::getData(self::WEIGHT);
    }

    /**
     * @param $weight
     * @return int|void
     */
    public function setWeight($weight)
    {
        $this->setData(self::WEIGHT, $weight);
    }
}

<?php

namespace Sandbox\Database\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

interface PowerbankInterface extends ExtensibleDataInterface
{
    const POWERBANK_ID = 'powerbank_id';
    const NAME = 'name';
    const CAPACITY = 'capacity';
    const WEIGHT = 'weight';

    /**
     * @return int
     */
    public function getPowerbankId();

    /**
     * @return string
     */
    public function getName();

    /**
     * @return string
     */
    public function setName($name);

    /**
     * @return int
     */
    public function getCapacity();

    /**
     * @return int
     */
    public function setCapacity($capacity);

    /**
     * @return int
     */
    public function getWeight();

    /**
     * @return int
     */
    public function setWeight($weight);
}

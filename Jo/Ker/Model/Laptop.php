<?php

namespace Jo\Ker\Model;

use Jo\Ker\Api\Data\LaptopInterface;
use Magento\Framework\Model\AbstractExtensibleModel;
use Jo\Ker\Model\ResourceModel\Laptop as ResourceModel;

class Laptop extends AbstractExtensibleModel implements LaptopInterface
{
    const CACHE_TAG = 'laptop';
    /**
     * @var string
     */
    protected $_eventPrefix = 'laptop_model';

    protected function _construct()
    {
        $this->_init(ResourceModel::class);
    }

    public function getId()
    {
        return parent::getData(self::LAPTOP_ID);
    }

    public function getName()
    {
        return parent::getData(self::NAME);
    }

    public function setName($name)
    {
        return parent::setData(self::NAME, $name);
    }

    public function getScreen()
    {
        return parent::getData(self::SCREEN);
    }

    public function setScreen($screen)
    {
        return parent::setData(self::SCREEN, $screen);
    }

    public function getProcessor()
    {
       return parent::getData(self::PROCESSOR);
    }

    public function setProcessor($processor)
    {
        return parent::setData(self::PROCESSOR, $processor);
    }

    public function getRam()
    {
        return parent::getData(self::RAM);
    }

    public function setRam($ram)
    {
        return parent::setData(self::RAM, $ram);
    }

    public function getDrive()
    {
        return parent::getData(self::DRIVE);
    }

    public function setDrive($drive)
    {
        return parent::setData(self::DRIVE, $drive);
    }

    public function getCreatedAt()
    {
        return parent::getData(self::CREATED_AT);
    }

    public function getUpdatedAt()
    {
        return parent::getData(self::UPDATED_AT);
    }
}

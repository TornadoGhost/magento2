<?php

namespace Jo\Ker\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

interface LaptopInterface extends ExtensibleDataInterface
{
    const LAPTOP_ID = 'laptop_id';
    const NAME = 'name';
    const SCREEN = 'screen';
    const PROCESSOR = 'processor';
    const RAM = 'ram';
    const DRIVE = 'drive';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    /**
     * @return int
     */
    public function getId();

    /**
     * @param $id
     * @return int
     */
    public function setId($id);

    /**
     * @return string
     */
    public function getName();

    /**
     * @param $name
     * @return string
     */
    public function setName($name);

    /**
     * @return float
     */
    public function getScreen();

    /**
     * @param $screen
     * @return float
     */
    public function setScreen($screen);

    /**
     * @return string
     */
    public function getProcessor();

    /**
     * @param $processor
     * @return string
     */
    public function setProcessor($processor);

    /**
     * @return int
     */
    public function getRam();

    /**
     * @param $ram
     * @return int
     */
    public function setRam($ram);

    /**
     * @return int
     */
    public function getDrive();

    /**
     * @param $drive
     * @return int
     */
    public function setDrive($drive);

    /**
     * @return mixed
     */
    public function getCreatedAt();

    /**
     * @return mixed
     */
    public function getUpdatedAt();
}

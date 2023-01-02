<?php

namespace Jo\Ker\Api;

use Jo\Ker\Api\Data\LaptopInterface;
use Jo\Ker\Api\Data\LaptopSearchResultInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

interface LaptopRepositoryInterface
{
    /**
     * @param int $id
     * @return LaptopInterface
     * @throws NoSuchEntityException
     */
    public function getById($id);

    /**
     * @param LaptopInterface $laptop
     * @return LaptopInterface
     * @throws CouldNotSaveException
     */
    public function save(LaptopInterface $laptop);

    /**
     * @param LaptopInterface $laptop
     * @return bool true on success
     * @throws CouldNotDeleteException
     */
    public function delete(LaptopInterface $laptop);

    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @return LaptopSearchResultInterface
     * @throws LocalizedException
     */
    public function getList(SearchCriteriaInterface $searchCriteria);
}

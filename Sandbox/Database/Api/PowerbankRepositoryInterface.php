<?php

namespace Sandbox\Database\Api;

use Bohdan\HelloWorld\Api\Data\BookSearchResultInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Sandbox\Database\Api\Data\PowerbankInterface;
use Sandbox\Database\Api\Data\PowerbankSearchResultInterface;
use Magento\Framework\Exception\NoSuchEntityException;
interface PowerbankRepositoryInterface
{
    /**
     * @param int $id
     * @return PowerbankInterface
     * @throws NoSuchEntityException
     */
    public function getById(int $id);

    /**
     * @param PowerbankInterface $powerbank
     * @return PowerbankInterface
     * @throws CouldNotSaveException
     */
    public function save(PowerbankInterface $powerbank);

    /**
     * @param PowerbankInterface $powerbank
     * @return PowerbankInterface
     * @throws CouldNotDeleteException
     */
    public function delete(PowerbankInterface $powerbank);

    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @return BookSearchResultInterface
     * @throws LocalizedException
     */
    public function getList(SearchCriteriaInterface $searchCriteria);
}

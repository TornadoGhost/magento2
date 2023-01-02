<?php

namespace Bohdan\HelloWorld\Api;

use Bohdan\HelloWorld\Api\Data\BookInterface;
use Bohdan\HelloWorld\Api\Data\BookSearchResultInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

interface BookRepositoryInterface
{
    /**
     * @param int $id
     * @return BookInterface
     * @throws NoSuchEntityException
     */
    public function getById($id);

    /**
     * @param BookInterface $book
     * @return BookInterface
     * @throws CouldNotSaveException
     */
    public function save(BookInterface $book);

    /**
     * @param BookInterface $book
     * @return bool true on success
     * @throws CouldNotDeleteException
     */
    public function delete(BookInterface $book);

    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @return BookSearchResultInterface
     * @throws LocalizedException
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);
}

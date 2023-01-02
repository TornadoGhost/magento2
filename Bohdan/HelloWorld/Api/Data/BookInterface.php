<?php

namespace Bohdan\HelloWorld\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

interface BookInterface extends ExtensibleDataInterface
{
    const BOOK_ID = 'book_id';
    const TITLE = 'title';
    const DESCRIPTION = 'description';
    const AUTHOR_ID = 'author_id';
    const PUBLISHER_ID = 'publisher_id';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    /**
     * @return int
     */
    public function getId();

    /**
     * @return string
     */
    public function getTitle();

    /**
     * @param $title
     * @return string
     */
    public function setTitle($title);

    /**
     * @return string
     */
    public function getDescription();

    /**
     * @param $description
     * @return string
     */
    public function setDescription($description);

    /**
     * @return int
     */
    public function getAuthorId();

    /**
     * @param $author_id
     * @return int
     */
    public function setAuthorId($author_id);

    /**
     * @return int
     */
    public function getPublisherId();

    /**
     * @param $publisher_id
     * @return int
     */
    public function setPublisherId($publisher_id);

}

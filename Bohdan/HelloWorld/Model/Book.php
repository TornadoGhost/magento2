<?php

namespace Bohdan\HelloWorld\Model;

use Bohdan\HelloWorld\Api\Data\BookInterface;
use Bohdan\HelloWorld\Model\ResourceModel\Book as ResourceModel;
use Magento\Framework\Model\AbstractExtensibleModel;
use Magento\Framework\DataObject\IdentityInterface;
use Bohdan\HelloWorld\Model\BookFactory;

class Book extends AbstractExtensibleModel implements BookInterface, IdentityInterface
{
    const CACHE_TAG = 'book';
    /**
     * @var string
     */
    protected $_eventPrefix = 'book_model';

    /**
     * Initialize magento model.
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(ResourceModel::class);
    }

    public function getId()
    {
        return parent::getData(self::BOOK_ID);
    }

    public function getTitle()
    {
        return parent::getData(self::TITLE);
    }

    public function setTitle($title)
    {
        $this->setData(self::TITLE, $title);
    }

    public function getDescription()
    {
        return parent::getData(self::DESCRIPTION);
    }

    public function setDescription($description)
    {
        $this->setData(self::DESCRIPTION, $description);
    }

    public function getAuthorId()
    {
        return parent::getData(self::AUTHOR_ID);
    }

    public function setAuthorId($author_id)
    {
        $this->setData(self::AUTHOR_ID, $author_id);
    }

    public function getPublisherId()
    {
        return parent::getData(self::PUBLISHER_ID);
    }

    public function setPublisherId($publisher_id)
    {
        $this->setData(self::PUBLISHER_ID, $publisher_id);
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getBookId()];
    }

    public function getAuthor()
    {
        $this->_resource->select()->joinLeft(['author'], 'main_table.author_id = author.author_id');
        return $this;
    }
}

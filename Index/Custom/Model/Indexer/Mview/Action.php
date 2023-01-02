<?php

namespace Index\Custom\Model\Indexer\Mview;

use Index\Custom\Model\Indexer\Test;
use Magento\Framework\Mview\ActionInterface;
use Magento\Framework\Indexer\IndexerInterfaceFactory;

class Action implements ActionInterface
{
    /**
     * @var IndexerInterfaceFactory
     */
    private $indexerFactory;

    /**
     * @param IndexerInterfaceFactory $indexerFactory
     */
    public function __construct(IndexerInterfaceFactory $indexerFactory)
    {
        $this->indexerFactory = $indexerFactory;
    }

    /**
     * Execute materialization on ids entities
     *
     * @param int[] $ids
     * @return void
     */
    public function execute($ids)
    {
        /** @var \Magento\Framework\Indexer\IndexerInterface $indexer */
        $indexer = $this->indexerFactory->create()->load(Test::INDEXER_ID);
        $indexer->reindexList($ids);
        $mongoDbConnection = new \MongoDB\Client("mongodb://root:root@mongo:27017/");
        $mongoDB = $mongoDbConnection->test->indexes;
        $mongoDB->insertOne([
            'ids' => $ids
        ]);
    }
}

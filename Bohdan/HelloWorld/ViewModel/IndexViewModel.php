<?php

namespace Bohdan\HelloWorld\ViewModel;

/*require __DIR__ . '/../../../../autoload.php';*/

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;

use Magento\Framework\Indexer\Config\DependencyInfoProvider;
use Magento\Framework\Indexer\IndexerRegistry;
use Magento\Indexer\Model\IndexerFactory;
use Magento\Indexer\Model\Indexer\Collection;

use Magento\Framework\View\Element\Block\ArgumentInterface;

class IndexViewModel extends AbstractHelper implements ArgumentInterface
{
    private $indexFactory;
    private $indexCollection;
    protected $indexerRegistry;
    protected $dependencyInfoProvider;

    public function __construct(Context               $context,
                                IndexerFactory        $indexFactory,
                                Collection            $indexCollection,
                                IndexerRegistry       $indexerRegistry,
    )
    {
        parent::__construct($context);
        $this->indexCollection = $indexCollection;
        $this->indexFactory = $indexFactory;
        $this->indexerRegistry = $indexerRegistry;
    }

    public function hello()
    {
        return 'Its IndexViewModel';
    }

    public function manualIndexing()
    {
        $mongoDbConnection = new \MongoDB\Client("mongodb://root:root@mongo:27017/");
        $mongoDB = $mongoDbConnection->test->indexes;


        $indexes = $this->indexCollection->getAllIds();

        $data1 = $this->indexCollection->loadData();
        $data2 = $this->indexCollection->getItems();
        $d2 = $data2[0]->getId();

        $indexFactory = $this->indexFactory->create()->load($indexes[0]);
        $test1 = $indexFactory->getLatestUpdated();

//        $indexer = $this->indexerRegistry->get($insideIndex);

//        $indexFactory = $this->indexFactory->create()->load('design_config_grid');
//        $indexFactoryData = $indexFactory->getData();
//        $fields = $indexFactoryData['fieldsets'][0]['fields'];

        foreach ($indexes as $index) {
            $indexFactory = $this->indexFactory->create()->load($index);
            $indexId = $indexFactory->getId();
            $indexFields = $indexFactory->getFieldsets();
            $fieldsMongo = array();
            if(!empty($indexFields)){
                foreach ($indexFields as $field){
                    $newField = $field['fields'];
                    $fieldsMongo = array_merge($fieldsMongo, $newField);
                }
            }

            $indexTitle = $indexFactory->getTitle();
            $time = date(DATE_RFC2822);
            $indexFactory->reindexRow($index);

            if(empty($fieldsMongo)){
                $fieldsMongo = 'No fields';
            }
            $mongoDB->insertOne([
                'indexer_id' => "$indexId",
                'indexer_title' => "$indexTitle",
                'reindex_data' => "$time",
                'fields' => $fieldsMongo
            ]);

            unset($fieldsMongo);
        }

        return "ok";
    }
}

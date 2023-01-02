<?php

namespace Index\Custom\Model\Indexer;

use InvalidArgumentException;
use Magento\CatalogSearch\Model\Indexer\Fulltext\Action\FullFactory;
use Magento\CatalogSearch\Model\Indexer\IndexerHandlerFactory;
use Magento\CatalogSearch\Model\ResourceModel\Fulltext as FulltextResource;
use Magento\Elasticsearch\Model\Indexer\IndexerHandler;
use Magento\Framework\App\DeploymentConfig;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Indexer\DimensionProviderInterface;
use Magento\Framework\Indexer\SaveHandler\IndexerInterface;
use Magento\Indexer\Model\ProcessManager;
use Magento\Store\Model\StoreDimensionProvider;

class Test implements
    \Magento\Framework\Indexer\ActionInterface,
    \Magento\Framework\Mview\ActionInterface,
    \Magento\Framework\Indexer\DimensionalIndexerInterface
{

    public const INDEXER_ID = 'index_custom_indexer';

    private const BATCH_SIZE = 1000;

    private $fullAction;

    protected $data;

    /**
     * @var IndexerHandlerFactory
     */
    private $indexerHandlerFactory;

    private $fulltextResource;

    private $dimensionProvider;

    private $processManager;

    private $batchSize;

    private $deploymentConfig;

    private $attribute;

    private const DEPLOYMENT_CONFIG_INDEXER_BATCHES = 'indexer/batch_size/';


    public function __construct(
//        \Magento\Eav\Model\Entity\Attribute $attribute,
        FullFactory                $fullActionFactory,
        IndexerHandlerFactory      $indexerHandlerFactory,
        FulltextResource           $fulltextResource,
        DimensionProviderInterface $dimensionProvider,
        array                      $data,
        ProcessManager             $processManager = null,
        ?int                       $batchSize = null,
        ?DeploymentConfig          $deploymentConfig = null
    )
    {
//        $this->attribute = $attribute;
        $this->fullAction = $fullActionFactory->create(['data' => $data]);
        $this->indexerHandlerFactory = $indexerHandlerFactory;
        $this->fulltextResource = $fulltextResource;
        $this->dimensionProvider = $dimensionProvider;
        $this->data = $data;
        $this->processManager = $processManager ?: ObjectManager::getInstance()->get(ProcessManager::class);
        $this->batchSize = $batchSize ?? self::BATCH_SIZE;
        $this->deploymentConfig = $deploymentConfig ?: ObjectManager::getInstance()->get(DeploymentConfig::class);
    }

    public function executeByDimensions(array $dimensions, \Traversable $entityIds = null)
    {
        if (count($dimensions) > 1 || !isset($dimensions[StoreDimensionProvider::DIMENSION_NAME])) {
            throw new InvalidArgumentException('Indexer "' . self::INDEXER_ID . '" support only Store dimension');
        }
        $mongoDbConnection = new \MongoDB\Client("mongodb://root:root@mongo:27017/");
        $mongoDB = $mongoDbConnection->test->indexes2;

        $storeId = $dimensions[StoreDimensionProvider::DIMENSION_NAME]->getValue();

        $document = $this->fullAction->rebuildStoreIndex($storeId);

        $batch = [];
        foreach ($document as $documentName => $documentValue) {
            $batch[$documentName] = $documentValue;
        }
        $test = [];

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

        foreach ($batch as $part){
            foreach ($part as $key=>$value){
                $attribute = $objectManager->get(\Magento\Eav\Model\Entity\Attribute::class)->load($key);
                $myAttribute = $attribute->getAttributeCode();
                $test[$myAttribute] = $value;
            }
            $mongoDB->insertOne(['batch' => $test]);
        }
    }


    /**
     * Execute full indexation
     *
     * @return void
     * @throws InvalidArgumentException
     */
    public function executeFull()
    {
        $userFunctions = [];

        foreach ($this->dimensionProvider->getIterator() as $dimension) {
            $userFunctions[] = function () use ($dimension) {
                $this->executeByDimensions($dimension);
            };
        }
        $this->processManager->execute($userFunctions);
    }

    public function executeList(array $ids)
    {
        // TODO: Implement executeList() method.
    }

    public function executeRow($id)
    {
        // TODO: Implement executeRow() method.
    }

    /**
     * Execute materialization on ids entities
     *
     * @param int[] $ids
     * @return void
     * @throws InvalidArgumentException
     */
    public function execute($ids): void
    {
        foreach ($this->dimensionProvider->getIterator() as $dimension) {
            $this->executeByDimensions($dimension, new \ArrayIterator($ids));
        }
    }
}

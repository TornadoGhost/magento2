<?php

namespace Tasks\PatchProduct\Setup\Patch\Data;

use Magento\CatalogInventory\Api\Data\StockItemInterfaceFactory;
use Magento\CatalogInventory\Model\Stock\StockItemRepository;

use Magento\Downloadable\Api\Data\SampleInterfaceFactory;
use Magento\Downloadable\Api\SampleRepositoryInterface;

use Magento\Bundle\Model\OptionFactory;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;

use Magento\Catalog\Model\ProductLink\LinkFactory;
use Magento\Bundle\Api\Data\LinkInterfaceFactory;

use Magento\ConfigurableProduct\Model\Product\Type\Configurable\Attribute;
use Magento\Catalog\Api\Data\ProductCustomOptionInterface;
use Magento\Catalog\Api\Data\ProductAttributeInterface;
use Magento\Catalog\Api\Data\ProductLinkInterface;
use Magento\Catalog\Model\CategoryLinkManagement;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\ProductFactory;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\CatalogInventory\Model\StockRegistry;

use Magento\Downloadable\Api\Data\LinkInterfaceFactory as DownloadableLinkInterfaceFactory;
use Magento\Downloadable\Api\LinkRepositoryInterface as DownloadableLinkRepositoryInterface;

use Magento\Eav\Model\AttributeRepository;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\StateException;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\App\State;
use Magento\InventoryApi\Api\StockRepositoryInterface;
use Magento\ProductLinksSampleData\Model\ProductLink;
use Magento\Store\Model\StoreManagerInterface;

class CreateProduct implements DataPatchInterface
{
    private $productFactory;

    private $productRepository;

    private $categoryLinkManagement;

    private $productStockRepository;

    private $stockRegistry;

    private $productLink;

    private $productCustomOption;

    private $attribute;

    private $configurable;

    private $linkFactory;

    private $linkInterfaceFactory;

    private $optionFactory;

    private $storeManager;

    private $downloadableLinkRepository;

    private $downloadableLinkInterface;

    private $sampleRepository;

    private $sampleInterfaceFactory;

    private $stockItemInterfaceFactory;

    private $stockItemRepository;


    public function __construct(StockItemInterfaceFactory           $stockItemInterfaceFactory,
                                StockItemRepository                 $stockItemRepository,
                                ProductFactory                      $productFactory,
                                DownloadableLinkInterfaceFactory    $downloadableLinkInterface,
                                LinkFactory                         $linkFactory,
                                LinkInterfaceFactory                $linkInterfaceFactory,
                                DownloadableLinkRepositoryInterface $downloadableLinkRepository,
                                ProductRepositoryInterface          $productRepository,
                                CategoryLinkManagement              $categoryLinkManagement,
                                State                               $state,
                                StockRepositoryInterface            $productStockRepository,
                                StockRegistry                       $stockRegistry,
                                ProductLinkInterface                $productLink,
                                ProductCustomOptionInterface        $productCustomOption,
                                Attribute                           $attribute,
                                Configurable                        $configurable,
                                OptionFactory                       $optionFactory,
                                StoreManagerInterface               $storeManager,
                                SampleRepositoryInterface           $sampleRepository,
                                SampleInterfaceFactory              $sampleInterfaceFactory)
    {
        $this->stockItemInterfaceFactory = $stockItemInterfaceFactory;
        $this->stockItemRepository = $stockItemRepository;

        $this->sampleInterfaceFactory = $sampleInterfaceFactory;
        $this->sampleRepository = $sampleRepository;

        $this->downloadableLinkInterface = $downloadableLinkInterface;
        $this->downloadableLinkRepository = $downloadableLinkRepository;

        $this->productFactory = $productFactory;
        $this->productRepository = $productRepository;
        $this->categoryLinkManagement = $categoryLinkManagement;
        $this->productStockRepository = $productStockRepository;
        $this->stockRegistry = $stockRegistry;
        $this->productLink = $productLink;
        $this->productCustomOption = $productCustomOption;
        $this->attribute = $attribute;
        $this->configurable = $configurable;
        $this->linkFactory = $linkFactory;
        $this->optionFactory = $optionFactory;
        $this->linkInterfaceFactory = $linkInterfaceFactory;
        $this->storeManager = $storeManager;
        $state->setAreaCode('adminhtml');;
    }


    /**
     * @throws StateException
     * @throws CouldNotSaveException
     * @throws InputException
     */
    public function apply()
    {
        //simple product
        /*$product = $this->productFactory->create();

        $categoryIds = [
            3,
            4
        ];

        $productArray = [
            'simple' => [
                'sku' => 'MySkuPants',
                'name' => 'Kelvin Slay',
                'attribute_set_id' => 10,
                'qty' => 69,
                'status' => 1,
                'price' => 50,
                'weight' => 100,
                'visibility' => 4,
                'type_id' => 'simple',

                'attributes' => [
                    'color' => 50,
                    'size' => 96,
                    'style_bottom' => 104,
                    'activity' => 6,
                    'material' => 32,
                    'pattern' => 196,
                    'climate' => '202,206',
                    'news_from_date' => '2022-12-04',
                    'news_to_date' => '2022-12-06',
                    'country_of_manufacture' => 'UA',
                    'eco_collection' => '1',
                    'performance_fabric' => 1,
                    'erin_recommends' => '1',
                    'new' => 1,
                    'sale' => 1
                ]
            ],
        ];

        foreach ($productArray as $key => $data) {
            switch ($key) {
                case 'simple':
                    $product->setSku($data['sku'])
                        ->setCategoryIds(array(1,2))
                        ->setName($data['name'])
                        ->setStockData(['qty' => 69, 'is_in_stock' => 1])
                        ->setAttributeSetId($data['attribute_set_id'])
                        ->setStatus($data['status'])
                        ->setPrice($data['price'])
                        ->setWeight($data['weight'])
                        ->setVisibility($data['visibility'])
                        ->setTypeid($data['type_id'])
                        ->setStockData(
                            [
                                'qty' => 1, // Manage Stock
                                'is_in_stock' => 1, // Stock Availability
                            ]
                        );
                    foreach ($data['attributes'] as $code => $value) {
                        $product->setData($code, $value);
                    }
                    $this->productRepository->save($product);
                    break;
            }
        }

        $this->categoryLinkManagement->assignProductToCategories(
            $product->getSku(),
            $categoryIds
        );*/

        //configurable product
        $product = $this->productFactory->create();

        $product->setName('Own attr and set Product'); // Set Product Name
        $product->setTypeId('configurable'); // Set Product Type Id
        $product->setAttributeSetId(17); // Set Attribute Set ID
        $product->setSku('own-configurable-product'); // Set SKU
        $product->setStatus(1); // Set Status
        $product->setWeight(5); // Set Weight
        $product->setTaxClassId(2); // Set Tax Class Id
        $product->setWebsiteIds([1]); // Set Website Ids
        $product->setVisibility(4);
        $product->setCategoryIds([3,19,34]); // Assign Category Ids
        $product->setPrice(100); // Product Price
        $product->setData('style_bottom', '104,106');
        $product->setData('material', 143);
        $product->setData('pattern', 198);
        $product->setData('climate', '206,207,209');
        $product->setStockData(
            [
                'use_config_manage_stock' => 0, // Use Config Settings Checkbox
                'manage_stock' => 1, // Manage Stock
                'is_in_stock' => 1, // Stock Availability
            ]
        );
    //        $size_attr_id = 142;
    //        $color_attr_id = 93;
    //        $product->getTypeInstance()->setUsedProductAttributeIds([$color_attr_id, $size_attr_id], $product);
    //        $configurableAttributesData = $product->getTypeInstance()->getConfigurableAttributesAsArray($product);
    //        $product->setConfigurableAttributesData($configurableAttributesData);

        $product = $this->productRepository->save($product);

        $prId = $product->getId();
        $attributeModel = $this->attribute;
        $attrArray = [93, 142, 157];
        $position = 0;
        foreach ($attrArray as $attributeId) {
            $data = array('attribute_id' => $attributeId, 'product_id' => $prId, 'position' => $position);
            $position++;
            $attributeModel->setData($data)->save();
        }

        $productId = $product->getId();
        $associatedProductIds = [2148]; // Add Your Associated Product Ids.
            $configurable_product = $this->productRepository->getById($productId); // Load Configurable Product
            $configurable_product->setAssociatedProductIds($associatedProductIds); // Setting Associated Products
            $this->productRepository->save($configurable_product);

        //grouped product
        /*$groupedProduct = $this->productFactory->create();
        $groupedProduct->setSku('grouped-product')
            ->setName('Sample Grouped Product')
            ->setTypeId('grouped')
            ->setAttributeSetId(11)
            ->setStatus(1)
            ->setWeight(1)
            ->setVisibility(4)
            ->setWebsiteIds([1])
            ->setCategoryIds([3, 5])
            ->setData('activity', '6,13,22')
            ->setData('material', '33,35,37')
            ->setData('gender', '80,81,82,83,84')
            ->setData('category_gear', '85,87')
            ->setData('is_in_stock', 1);
        $groupedProduct = $this->productRepository->save($groupedProduct);
//        $this->categoryLinkManagement->assignProductToCategories($groupedProduct->getSku(), [3,5]);

        $productIds = [33, 34];
        $associated = [];
        $position = 0;
        foreach ($productIds as $productId) {
            $position++;
            $linkedProduct = $this->productRepository->getById($productId);
            $productLink = $this->linkFactory->create();
            $productLink->setSku($groupedProduct->getSku())
                ->setLinkType('associated') // Set Link Type
                ->setLinkedProductSku($linkedProduct->getSku()) // Set Link Product SKU
                ->setLinkedProductType($linkedProduct->getTypeId()) // Set Link Product Type
                ->setPosition($position) // Set Position
                ->getExtensionAttributes()
                ->setQty(1);
            // Set Associated Product Default QTY
            $associated[] = $productLink;
        }
        $groupedProduct->setProductLinks($associated);
        $this->productRepository->save($groupedProduct);*/

        //bundle product
        /*$bundleProduct = $this->productFactory->create();
        $bundleProduct->setSku('bundle-product')
            ->setName('Sample Bundle Product')
            ->setTypeId('bundle')
            ->setAttributeSetId(11)
            ->setVisibility(4)
            ->setWebsiteIds([1])
            ->setCategoryIds([3, 5])
            ->setPriceType(0)
            ->setPriceView(0)
            ->setData('activity', '8,11')
            ->setGender(84)
            ->setData('category_gear', 87);
        $bundleProduct = $this->productRepository->save($bundleProduct);

        $product = $bundleProduct;
        // Set bundle product items
        $product->setBundleOptionsData(
            [
                [
                    'title' => 'Sprite Stasis Ball',
                    'default_title' => 'Sprite Stasis Ball',
                    'type' => 'buttons',
                    'required' => 1,
                    'delete' => ''
                ],
                [
                    'title' => 'Sprite Foam Yoga Brick',
                    'default_title' => 'Sprite Foam Yoga Brick',
                    'type' => 'buttons',
                    'required' => 1,
                    'delete' => ''
                ],
                [
                    'title' => 'Sprite Yoga Strap',
                    'default_title' => 'Sprite Yoga Strap',
                    'type' => 'buttons',
                    'required' => 1,
                    'delete' => ''
                ],
                [
                    'title' => 'Sprite Foam Roller',
                    'default_title' => 'Sprite Foam Roller',
                    'type' => 'buttons',
                    'required' => 1,
                    'delete' => ''
                ],
            ]
        )->setBundleSelectionsData(
            [
                [
                    ['product_id' => 26, 'selection_qty' => 1, 'selection_can_change_qty' => 1, 'delete' => '', 'is_default' => 1],
                    ['product_id' => 29, 'selection_qty' => 1, 'selection_can_change_qty' => 1, 'delete' => ''],
                    ['product_id' => 32, 'selection_qty' => 1, 'selection_can_change_qty' => 1, 'delete' => '']
                ],
                [
                    ['product_id' => 21, 'selection_qty' => 1, 'selection_can_change_qty' => 1, 'delete' => '', 'is_default' => 1]
                ],
                [
                    ['product_id' => 33, 'selection_qty' => 1, 'selection_can_change_qty' => 1, 'delete' => '', 'is_default' => 1],
                    ['product_id' => 34, 'selection_qty' => 1, 'selection_can_change_qty' => 1, 'delete' => ''],
                    ['product_id' => 35, 'selection_qty' => 1, 'selection_can_change_qty' => 1, 'delete' => '']
                ],
                [
                    ['product_id' => 22, 'selection_qty' => 1, 'selection_can_change_qty' => 1, 'delete' => '', 'is_default' => 1]
                ]
            ]
        );

        if ($product->getBundleOptionsData()) {
            $options = [];

            foreach ($product->getBundleOptionsData() as $key => $optionData) {
                if (!(bool)$optionData['delete']) {
                    $option = $this->optionFactory->create();
                    $option->setData($optionData);
                    $option->setSku($product->getSku());
                    $option->setOptionId(null);
                    $links = [];
                    $bundleLinks = $product->getBundleSelectionsData();
                    if (!empty($bundleLinks[$key])) {
                        foreach ($bundleLinks[$key] as $linkData) {
                            if (!(bool)$linkData['delete']) {
                                $link = $this->linkInterfaceFactory->create();
                                $link->setData($linkData);
                                $linkProduct = $this->productRepository->getById($linkData['product_id']);
                                $link->setSku($linkProduct->getSku());
                                $link->setQty($linkData['selection_qty']);
                                if (isset($linkData['selection_can_change_qty'])) {
                                    $link->setCanChangeQuantity($linkData['selection_can_change_qty']);
                                }
                                if(isset($linkData['is_default'])){
                                    $link->setIsDefault($linkData['is_default']);
                                }
                                $links[] = $link;
                            }
                        }
                        $option->setProductLinks($links);
                        $options[] = $option;
                    }
                }
            }

            $extension = $product->getExtensionAttributes();
            $extension->setBundleProductOptions($options);
            $product->setExtensionAttributes($extension);
        }
        $this->productRepository->save($product);*/

        //downloadable product
        /*$downloadable_product = $this->productFactory->create();
        $downloadable_product->setSku('downloadable-product')
            ->setName('Sample Downloadable Product')
            ->setTypeId('downloadable')
            ->setAttributeSetId(14)
            ->setStatus(1)
            ->setVisibility(4)
            ->setTaxClassId(2)
            ->setWebsiteIds([1])
            ->setCategoryIds([10])
            ->setQuantity(69)
            ->setStockStatusId(1)
            ->setData('format', 102)
            ->setPrice(100)
            ->setPriceType(0)
            ->setPriceView(0);
        $downloadable_product = $this->productRepository->save($downloadable_product);

        //downloadable information
        $storeManager = $this->storeManager;
        $baseUrl = $storeManager->getStore()->getBaseUrl();

        $link_repository = $this->downloadableLinkRepository;
        $sample_repository = $this->sampleRepository;

        $linkData = [
            [
                'title' => 'Transformers',
                'price' => 49,
                'number_of_downloads' => 10,
                'is_shareable' => 0,
                'link_type' => 'url',
                'link_url' => $baseUrl . 'transformers/transformers.mp4',
                'sample_title' => 'Transformers Demo',
                'sample_type' => 'url',
                'sample_url' => $baseUrl . 'transformers/transformers-demo.mp4',
                'is_unlimited' => 0,
            ],
            [
                'title' => 'Transformers 2',
                'price' => 49,
                'number_of_downloads' => 10,
                'is_shareable' => 0,
                'link_type' => 'url',
                'link_url' => $baseUrl . 'transformers/transformers-2.mp4',
                'sample_title' => 'Transformers 2 Demo',
                'sample_type' => 'url',
                'sample_url' => $baseUrl . 'transformers/transformers-2-demo.mp4',
                'is_unlimited' => 0,
            ],
            [
                'title' => 'Transformers 3',
                'price' => 49,
                'number_of_downloads' => 10,
                'is_shareable' => 0,
                'link_type' => 'url',
                'link_url' => $baseUrl . 'transformers/transformers-3.mp4',
                'sample_title' => 'Transformers 3 Demo',
                'sample_type' => 'url',
                'sample_url' => $baseUrl . 'transformers/transformers-3-demo.mp4',
                'is_unlimited' => 0,
            ]
        ];

        $productId = $downloadable_product->getSku();

        $position = 0;
        foreach ($linkData as $data){
            $position++;
            $link_interface = $this->downloadableLinkInterface->create();
            $link_interface->setTitle($data['title'])
                ->setPrice($data['price'])
                ->setNumberOfDownloads($data['number_of_downloads'])
                ->setIsShareable($data['is_shareable'])
                ->setLinkType($data['link_type'])
                ->setLinkUrl($data['link_url'])
                ->setSampleType($data['sample_type'])
                ->setSampleUrl($data['sample_url'])
                ->setIsUnlimited($data['is_unlimited'])
                ->setSortOrder($position);
            $link_repository->save($productId, $link_interface);

            $sample_interface = $this->sampleInterfaceFactory->create();
            $sample_interface->setTitle($data['sample_title'])
                ->setSampleType($data['sample_type'])
                ->setSampleUrl($data['sample_url'])
                ->setSortOrder($position);
            $sample_repository->save($productId, $sample_interface);
        }*/

        //virtual product
        /*$virtualProduct = $this->productFactory->create();
        $virtualProduct->setSku('virtual-product')
            ->setTypeId('virtual')
            ->setAttributeSetId(4)
            ->setName('Sample Virtual Product')
            ->setStatus(1)
            ->setVisibility(4)
            ->setPrice(89)
            ->setWebsiteIds([1])
            ->setCategoryIds([2])
            ->setStockData([
                'is_in_stock' => 1,
                'qty' => 77
            ]);
        $virtualProduct = $this->productRepository->save($virtualProduct);*/

//        $item = $this->stockItemInterfaceFactory->create();
//        $item->setProductId($virtualProduct->getId())
//            ->setStockId(1)
//            ->setQty(77);
//        $this->stockItemRepository->save($item);

    }

    public static function getDependencies()
    {
        return [];
    }

    public function getAliases()
    {
        return [];
    }

}

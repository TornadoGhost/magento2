<?php

namespace Tasks\Gift\Setup;

use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Tasks\Gift\Model\Attribute\Source\Gift as Source;
use Tasks\Gift\Model\Attribute\Backend\Gift as Backend;
use Tasks\Gift\Model\Attribute\Frontend\Gift as Frontend;

use Magento\Eav\Model\AttributeManagement;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class InstallData implements InstallDataInterface
{
    private $eavSetupFactory;
    private $attributeManagement;

    public function __construct(EavSetupFactory $eavSetupFactory, AttributeManagement $attributeManagement)
    {
        $this->eavSetupFactory = $eavSetupFactory;
        $this->attributeManagement = $attributeManagement;
    }

    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
        /*$eavSetup->removeAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'my_gift');*/

        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'my_gift',
            [
                'group' => 'General',
                'type' => 'varchar',
                'label' => 'Gift',
                'input' => 'select',
                'source' => Source::class,
                'frontend' => '',
                'backend' => '',
                'required' => false,
                'sort_order' => 50,
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'is_used_in_grid' => false,
                'is_visible_in_grid' => false,
                'is_filterable_in_grid' => false,
                'visible' => true,
            ]
        );

        /*$attributeGroup = 'Test Gift Group';
        $attributes = [
            'my_present' => [
                'group' => $attributeGroup,
                'type' => 'int',
                'entity_type_id' => 4,
                'backend' => '',
                'frontend' => '',
                'label' => 'Gift',
                'input' => 'select',
                'class' => '',
                'source' => Source::class,
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'default' => '',
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'visible_in_advanced_search' => false,
                'is_html_allowed_on_front'   => false,
                'used_for_promo_rules'       => true,
                'unique' => false,
                'apply_to' => 'simple,grouped,configurable,downloadable,virtual,bundle'
            ],
        ];

        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
        foreach ($attributes as $attribute_code => $attributeOptions) {
            $eavSetup->addAttribute(
                \Magento\Catalog\Model\Product::ENTITY,
                $attribute_code,
                $attributeOptions
            );
        }
//Assign
        foreach ($attributes as $attribute_code => $attributeOptions) {
            $this->attributeManagement->assign(
                'catalog_product',
                17,
                110,
                $attribute_code,
                100
            );
        }*/
        /*$eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
        );*/
    }
}

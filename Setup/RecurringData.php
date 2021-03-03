<?php
/**
 * @author      Benjamin Rosenberger <rosenberger@e-conomix.at>
 * @package MaxServ\YoastSeo\Setup
 * @copyright Copyright (c) 2021 E-CONOMIX GmbH (https://www.e-conomix.at)
 * @created 03.03.2021
 */

namespace MaxServ\YoastSeo\Setup;

use Magento\Catalog\Model\Category;
use Magento\Eav\Setup\EavSetup;
use Magento\Framework\App\ProductMetadata;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\TestFramework\Helper\Eav;

/**
 * Class RecurringData
 * @package MaxServ\YoastSeo\Setup
 */
class RecurringData implements InstallDataInterface
{
    /**
     * @var \Magento\Eav\Setup\EavSetupFactory
     */
    private $eavSetupFactory;
    /**
     * @var ProductMetadata
     */
    private $productMetadata;

    /**
     * RecurringData constructor.
     * @param \Magento\Eav\Setup\EavSetupFactory $eavSetupFactory
     */
    public function __construct(
        \Magento\Eav\Setup\EavSetupFactory $eavSetupFactory,
        ProductMetadata $productMetadata
    ) {
        $this->eavSetupFactory = $eavSetupFactory;
        $this->productMetadata = $productMetadata;
    }

    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        if (version_compare($this->productMetadata->getVersion(), "2.4.0") >= 0) {
            /** @var EavSetup $eavSetup */
            $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
            $categoryEntityId = $eavSetup->getEntityTypeId(Category::ENTITY);
            $this->updateCategoryImageFrontendInput($eavSetup, $categoryEntityId, 'yoast_facebook_image');
            $this->updateCategoryImageFrontendInput($eavSetup, $categoryEntityId, 'yoast_twitter_image');
        }
    }

    /**
     * @param EavSetup $eavSetup
     * @param $categoryEntityId
     * @param $attributeCode
     * @return bool
     */
    protected function updateCategoryImageFrontendInput($eavSetup, $categoryEntityId, $attributeCode)
    {
        if ($eavSetup->getAttribute($categoryEntityId, $attributeCode)['frontend_input'] == "media_image") {
            $eavSetup->updateAttribute($categoryEntityId, $attributeCode, 'frontend_input', 'image');
        }
    }
}
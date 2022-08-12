<?php
/**
 * @author andy
 * @email andyworkbase@gmail.com
 * @team MageCloud
 * @package MageCloud_Faq
 */
namespace MageCloud\Faq\Setup;

use Magento\Framework\Setup\{
    ModuleContextInterface,
    ModuleDataSetupInterface,
    InstallDataInterface
};

use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Catalog\Model\Category;

/**
 * Class InstallData
 * @package MageCloud\Faq\Setup
 */
class InstallData implements InstallDataInterface
{
    const CATEGORY_FAQ_GROUP = 'FAQ';

    /**
     * @var EavSetupFactory
     */
    private $eavSetupFactory;

    /**
     * InstallData constructor.
     * @param EavSetupFactory $eavSetupFactory
     */
    public function __construct(
        EavSetupFactory $eavSetupFactory
    ) {
        $this->eavSetupFactory = $eavSetupFactory;
    }

    /**
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Zend_Validate_Exception
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        /** @var EavSetup $eavSetup */
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
        $this->addCategoryAttribute($eavSetup, 'magecloud_faq_title', 'FAQ Title');
    }

    /**
     * @param EavSetup $eavSetup
     * @param string $attributeCode
     * @param null|string $attributeLabel
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Zend_Validate_Exception
     */
    private function addCategoryAttribute($eavSetup, $attributeCode, $attributeLabel = null)
    {
        if ($eavSetup->getAttribute(Category::ENTITY, $attributeCode)) {
            return $this;
        }
        if (null === $attributeLabel) {
            $attributeLabel = ucwords(str_replace('_', ' ', $attributeCode));
        }

        $eavSetup->addAttribute(
            Category::ENTITY,
            $attributeCode,
            [
                'type' => 'varchar',
                'label' => $attributeLabel,
                'input' => 'text',
                'sort_order' => 1,
                'visible' => true,
                'required' => false,
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                'group' => self::CATEGORY_FAQ_GROUP,
            ]
        );
        return $this;
    }
}
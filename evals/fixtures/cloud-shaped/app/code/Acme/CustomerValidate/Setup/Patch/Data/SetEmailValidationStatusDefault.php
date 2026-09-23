<?php
declare(strict_types=1);

namespace Acme\CustomerValidate\Setup\Patch\Data;

use Magento\Customer\Model\Customer;
use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;

class SetEmailValidationStatusDefault implements DataPatchInterface
{
    private const ATTRIBUTE_CODE = 'acme_email_validation_status';

    private const DEFAULT_VALUE = 'unverified';

    public function __construct(
        private readonly ModuleDataSetupInterface $moduleDataSetup,
        private readonly CustomerSetupFactory $customerSetupFactory,
    ) {
    }

    public function apply(): self
    {
        $connection = $this->moduleDataSetup->getConnection();
        $connection->startSetup();

        $customerSetup = $this->customerSetupFactory->create(['setup' => $this->moduleDataSetup]);
        $attributeId = $customerSetup->getAttributeId(Customer::ENTITY, self::ATTRIBUTE_CODE);

        if (!$attributeId) {
            $customerSetup->addAttribute(
                Customer::ENTITY,
                self::ATTRIBUTE_CODE,
                [
                    'type' => 'varchar',
                    'label' => 'Email Validation Status',
                    'input' => 'text',
                    'required' => false,
                    'visible' => false,
                    'user_defined' => true,
                    'system' => false,
                    'default' => self::DEFAULT_VALUE,
                ]
            );
        } else {
            $customerSetup->updateAttribute(
                Customer::ENTITY,
                self::ATTRIBUTE_CODE,
                'default',
                self::DEFAULT_VALUE
            );
        }

        $connection->endSetup();

        return $this;
    }

    public static function getDependencies(): array
    {
        return [];
    }

    public function getAliases(): array
    {
        return [];
    }
}

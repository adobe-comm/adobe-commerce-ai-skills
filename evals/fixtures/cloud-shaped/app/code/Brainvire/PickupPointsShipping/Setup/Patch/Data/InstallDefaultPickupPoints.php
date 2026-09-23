<?php
declare(strict_types=1);

namespace Brainvire\PickupPointsShipping\Setup\Patch\Data;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;

class InstallDefaultPickupPoints implements DataPatchInterface
{
    public function __construct(
        private readonly ModuleDataSetupInterface $moduleDataSetup,
    ) {
    }

    public function apply(): self
    {
        $connection = $this->moduleDataSetup->getConnection();
        $table = $this->moduleDataSetup->getTable('brainvire_pickup_point');

        $connection->startSetup();

        $existing = (int) $connection->fetchOne(
            $connection->select()->from($table, ['COUNT(*)'])
        );
        if ($existing === 0) {
            $connection->insertMultiple($table, [
                [
                    'name' => 'Downtown Service Center',
                    'street' => '100 Market Street',
                    'city' => 'Austin',
                    'postcode' => '78701',
                    'country_id' => 'US',
                    'is_active' => 1,
                    'sort_order' => 10,
                ],
                [
                    'name' => 'Northside Pickup Locker',
                    'street' => '4500 Research Blvd',
                    'city' => 'Austin',
                    'postcode' => '78759',
                    'country_id' => 'US',
                    'is_active' => 1,
                    'sort_order' => 20,
                ],
            ]);
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

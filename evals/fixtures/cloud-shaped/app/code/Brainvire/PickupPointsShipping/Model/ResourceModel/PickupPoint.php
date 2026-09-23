<?php
declare(strict_types=1);

namespace Brainvire\PickupPointsShipping\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class PickupPoint extends AbstractDb
{
    protected function _construct(): void
    {
        $this->_init('brainvire_pickup_point', 'entity_id');
    }
}

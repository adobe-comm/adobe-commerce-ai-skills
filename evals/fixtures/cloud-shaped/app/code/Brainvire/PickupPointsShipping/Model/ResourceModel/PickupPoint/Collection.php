<?php
declare(strict_types=1);

namespace Brainvire\PickupPointsShipping\Model\ResourceModel\PickupPoint;

use Brainvire\PickupPointsShipping\Model\PickupPoint;
use Brainvire\PickupPointsShipping\Model\ResourceModel\PickupPoint as PickupPointResource;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected function _construct(): void
    {
        $this->_init(PickupPoint::class, PickupPointResource::class);
    }
}

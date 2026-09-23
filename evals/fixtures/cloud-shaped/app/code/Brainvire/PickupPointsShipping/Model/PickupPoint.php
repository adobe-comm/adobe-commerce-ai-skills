<?php
declare(strict_types=1);

namespace Brainvire\PickupPointsShipping\Model;

use Magento\Framework\Model\AbstractModel;

class PickupPoint extends AbstractModel
{
    protected function _construct(): void
    {
        $this->_init(ResourceModel\PickupPoint::class);
    }
}

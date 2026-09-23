<?php
declare(strict_types=1);

namespace Brainvire\PickupPointsShipping\Plugin\Quote\Address;

use Magento\Quote\Model\Quote\Address;
use Magento\Quote\Model\Quote\Address\ToOrderAddress;
use Magento\Sales\Api\Data\OrderAddressExtensionFactory;
use Magento\Sales\Api\Data\OrderAddressInterface;

class ToOrderAddressPlugin
{
    public function __construct(
        private readonly OrderAddressExtensionFactory $orderAddressExtensionFactory,
    ) {
    }

    public function afterConvert(
        ToOrderAddress $subject,
        OrderAddressInterface $orderAddress,
        Address $quoteAddress
    ): OrderAddressInterface {
        $pickupPointId = $quoteAddress->getData('pickup_point_id');
        if ($pickupPointId === null || $pickupPointId === '') {
            return $orderAddress;
        }

        $pickupPointId = (int) $pickupPointId;
        $orderAddress->setData('pickup_point_id', $pickupPointId);

        $extensionAttributes = $orderAddress->getExtensionAttributes()
            ?? $this->orderAddressExtensionFactory->create();
        $extensionAttributes->setPickupPointId($pickupPointId);
        $orderAddress->setExtensionAttributes($extensionAttributes);

        return $orderAddress;
    }
}

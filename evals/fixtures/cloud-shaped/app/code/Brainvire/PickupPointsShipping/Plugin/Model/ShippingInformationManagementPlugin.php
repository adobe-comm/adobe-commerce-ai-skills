<?php
declare(strict_types=1);

namespace Brainvire\PickupPointsShipping\Plugin\Model;

use Brainvire\PickupPointsShipping\Model\Carrier\PickupPoints;
use Magento\Checkout\Api\Data\ShippingInformationInterface;
use Magento\Checkout\Model\ShippingInformationManagement;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Api\Data\AddressExtensionFactory;

class ShippingInformationManagementPlugin
{
    public function __construct(
        private readonly CartRepositoryInterface $cartRepository,
        private readonly AddressExtensionFactory $addressExtensionFactory,
    ) {
    }

    /**
     * @param mixed $result
     * @return mixed
     */
    public function afterSaveAddressInformation(
        ShippingInformationManagement $subject,
        $result,
        int $cartId,
        ShippingInformationInterface $addressInformation
    ) {
        $pickupPointId = PickupPoints::extractPickupPointId($addressInformation->getShippingMethod());
        if ($pickupPointId === null) {
            return $result;
        }

        $quote = $this->cartRepository->getActive($cartId);
        $shippingAddress = $quote->getShippingAddress();
        if ($shippingAddress === null) {
            return $result;
        }

        $quoteExtension = $shippingAddress->getExtensionAttributes()
            ?? $this->addressExtensionFactory->create();
        $quoteExtension->setPickupPointId($pickupPointId);
        $shippingAddress->setExtensionAttributes($quoteExtension);
        $shippingAddress->setData('pickup_point_id', $pickupPointId);
        $this->cartRepository->save($quote);

        return $result;
    }
}

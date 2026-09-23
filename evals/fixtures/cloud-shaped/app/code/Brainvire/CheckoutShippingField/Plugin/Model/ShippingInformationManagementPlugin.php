<?php
declare(strict_types=1);

namespace Brainvire\CheckoutShippingField\Plugin\Model;

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
     * Persist delivery instructions on the quote shipping address when the shipping step is saved.
     *
     * @param mixed $result
     * @return mixed
     */
    public function afterSaveAddressInformation(
        ShippingInformationManagement $subject,
        $result,
        int $cartId,
        ShippingInformationInterface $addressInformation
    ) {
        $apiAddress = $addressInformation->getShippingAddress();
        if ($apiAddress === null) {
            return $result;
        }

        $extensionAttributes = $apiAddress->getExtensionAttributes();
        $instructions = $extensionAttributes?->getBvDeliveryInstructions();
        if ($instructions === null) {
            return $result;
        }

        $quote = $this->cartRepository->getActive($cartId);
        $shippingAddress = $quote->getShippingAddress();
        if ($shippingAddress === null) {
            return $result;
        }

        $quoteExtension = $shippingAddress->getExtensionAttributes()
            ?? $this->addressExtensionFactory->create();
        $quoteExtension->setBvDeliveryInstructions($instructions);
        $shippingAddress->setExtensionAttributes($quoteExtension);
        $shippingAddress->setData('bv_delivery_instructions', $instructions);
        $this->cartRepository->save($quote);

        return $result;
    }
}

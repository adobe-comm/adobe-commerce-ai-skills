define([
    'mage/utils/wrapper',
    'Magento_Checkout/js/model/quote',
    'uiRegistry'
], function (wrapper, quote, registry) {
    'use strict';

    return function (setShippingInformationAction) {
        return wrapper.wrap(setShippingInformationAction, function (originalAction) {
            var shippingAddress = quote.shippingAddress(),
                checkoutProvider = registry.get('checkoutProvider'),
                instructions;

            if (shippingAddress && checkoutProvider) {
                instructions = checkoutProvider.get(
                    'shippingAddress.extension_attributes.bv_delivery_instructions'
                ) || '';

                shippingAddress.extensionAttributes = shippingAddress.extensionAttributes || {};
                shippingAddress.extensionAttributes.bv_delivery_instructions = instructions;
            }

            return originalAction();
        });
    };
});

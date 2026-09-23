define([
    'Magento_Ui/js/form/element/textarea',
    'Magento_Checkout/js/model/quote'
], function (Textarea, quote) {
    'use strict';

    return Textarea.extend({
        defaults: {
            listens: {
                value: 'onValueChange'
            }
        },

        /**
         * Keep Knockout quote shipping address in sync for set-shipping-information payload.
         */
        onValueChange: function (value) {
            var shippingAddress = quote.shippingAddress();

            if (!shippingAddress) {
                return;
            }

            if (shippingAddress.extensionAttributes === undefined) {
                shippingAddress.extensionAttributes = {};
            }

            shippingAddress.extensionAttributes.bv_delivery_instructions = value;
        }
    });
});

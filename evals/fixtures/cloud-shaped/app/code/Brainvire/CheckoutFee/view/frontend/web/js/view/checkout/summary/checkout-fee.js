define([
    'Magento_Checkout/js/view/summary/abstract-total'
], function (Component) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'Brainvire_CheckoutFee/checkout/summary/checkout-fee',
            title: 'Checkout Fee'
        },

        /**
         * @inheritdoc
         */
        getValue: function () {
            return this.getFormattedPrice(this.getPureValue());
        },

        /**
         * @return {Number}
         */
        getPureValue: function () {
            var totals = this.totals();
            if (!totals || !totals.total_segments) {
                return 0;
            }

            var i;
            for (i = 0; i < totals.total_segments.length; i++) {
                if (totals.total_segments[i].code === 'checkout_fee') {
                    return totals.total_segments[i].value;
                }
            }

            return 0;
        },

        /**
         * @return {Boolean}
         */
        isDisplayed: function () {
            return this.getPureValue() !== 0;
        }
    });
});

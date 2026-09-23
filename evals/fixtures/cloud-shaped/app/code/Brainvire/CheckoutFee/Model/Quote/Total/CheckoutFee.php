<?php
declare(strict_types=1);

namespace Brainvire\CheckoutFee\Model\Quote\Total;

use Brainvire\CheckoutFee\Model\Config;
use Magento\Framework\Phrase;
use Magento\Quote\Api\Data\ShippingAssignmentInterface;
use Magento\Quote\Model\Quote;
use Magento\Quote\Model\Quote\Address\Total;
use Magento\Quote\Model\Quote\Address\Total\AbstractTotal;

/**
 * Adds a flat checkout fee during quote address collect totals.
 */
class CheckoutFee extends AbstractTotal
{
    public const TOTAL_CODE = 'checkout_fee';

    public function __construct(
        private readonly Config $config
    ) {
        $this->setCode(self::TOTAL_CODE);
    }

    public function collect(
        Quote $quote,
        ShippingAssignmentInterface $shippingAssignment,
        Total $total
    ) {
        parent::collect($quote, $shippingAssignment, $total);

        $storeId = (int) $quote->getStoreId();
        if (!$this->config->isEnabled($storeId)) {
            return $this;
        }

        if (!$quote->getItemsCount() || !$shippingAssignment->getItems()) {
            return $this;
        }

        $fee = $this->config->getFeeAmount($storeId);
        if ($fee <= 0) {
            return $this;
        }

        $total->addTotalAmount(self::TOTAL_CODE, $fee);
        $total->addBaseTotalAmount(self::TOTAL_CODE, $fee);

        return $this;
    }

    public function fetch(Quote $quote, Total $total): array
    {
        $storeId = (int) $quote->getStoreId();
        if (!$this->config->isEnabled($storeId)) {
            return [];
        }

        $fee = $this->config->getFeeAmount($storeId);
        if ($fee <= 0) {
            return [];
        }

        return [
            'code' => self::TOTAL_CODE,
            'title' => $this->getLabel(),
            'value' => $fee,
        ];
    }

    public function getLabel(): Phrase
    {
        return __('Checkout Fee');
    }
}

<?php
declare(strict_types=1);

namespace Brainvire\CheckoutFee\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class Config
{
    private const XML_PATH_ENABLED = 'brainvire_checkout_fee/general/enabled';
    private const XML_PATH_FEE_AMOUNT = 'brainvire_checkout_fee/general/fee_amount';

    public function __construct(
        private readonly ScopeConfigInterface $scopeConfig
    ) {
    }

    public function isEnabled(?int $storeId = null): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_ENABLED,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    public function getFeeAmount(?int $storeId = null): float
    {
        return (float) $this->scopeConfig->getValue(
            self::XML_PATH_FEE_AMOUNT,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }
}

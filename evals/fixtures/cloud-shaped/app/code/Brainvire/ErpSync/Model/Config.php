<?php
declare(strict_types=1);

namespace Brainvire\ErpSync\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Encryption\EncryptorInterface;
use Magento\Store\Model\ScopeInterface;

class Config
{
    private const XML_PATH_SANDBOX_URL = 'brainvire_erp/order_export/sandbox_url';
    private const XML_PATH_API_KEY = 'brainvire_erp/order_export/api_key';
    private const XML_PATH_MAX_ATTEMPTS = 'brainvire_erp/order_export/max_attempts';
    private const XML_PATH_INITIAL_DELAY = 'brainvire_erp/order_export/initial_delay_seconds';
    private const XML_PATH_MAX_DELAY = 'brainvire_erp/order_export/max_delay_seconds';
    private const XML_PATH_CONNECT_TIMEOUT = 'brainvire_erp/order_export/connect_timeout_seconds';
    private const XML_PATH_READ_TIMEOUT = 'brainvire_erp/order_export/read_timeout_seconds';
    private const XML_PATH_STOCK_RESERVATION_ENABLED = 'brainvire_erp/stock_reservation/enabled';
    private const XML_PATH_STOCK_RESERVATION_URL = 'brainvire_erp/stock_reservation/reservation_url';
    private const XML_PATH_STOCK_RESERVATION_CONNECT_TIMEOUT =
        'brainvire_erp/stock_reservation/connect_timeout_seconds';
    private const XML_PATH_STOCK_RESERVATION_READ_TIMEOUT =
        'brainvire_erp/stock_reservation/read_timeout_seconds';
    private const XML_PATH_STOCK_RESERVATION_BLOCK_CHECKOUT =
        'brainvire_erp/stock_reservation/block_checkout_on_failure';

    public function __construct(
        private readonly ScopeConfigInterface $scopeConfig,
        private readonly EncryptorInterface $encryptor
    ) {
    }

    public function getSandboxUrl(?int $storeId = null): string
    {
        return (string) $this->scopeConfig->getValue(
            self::XML_PATH_SANDBOX_URL,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    public function getApiKey(?int $storeId = null): string
    {
        $encrypted = (string) $this->scopeConfig->getValue(
            self::XML_PATH_API_KEY,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
        if ($encrypted === '') {
            return '';
        }

        return $this->encryptor->decrypt($encrypted);
    }

    public function getMaxAttempts(?int $storeId = null): int
    {
        return max(1, (int) $this->scopeConfig->getValue(
            self::XML_PATH_MAX_ATTEMPTS,
            ScopeInterface::SCOPE_STORE,
            $storeId
        ));
    }

    public function getInitialDelaySeconds(?int $storeId = null): int
    {
        return max(0, (int) $this->scopeConfig->getValue(
            self::XML_PATH_INITIAL_DELAY,
            ScopeInterface::SCOPE_STORE,
            $storeId
        ));
    }

    public function getMaxDelaySeconds(?int $storeId = null): int
    {
        return max(1, (int) $this->scopeConfig->getValue(
            self::XML_PATH_MAX_DELAY,
            ScopeInterface::SCOPE_STORE,
            $storeId
        ));
    }

    public function getConnectTimeoutSeconds(?int $storeId = null): int
    {
        return max(1, (int) $this->scopeConfig->getValue(
            self::XML_PATH_CONNECT_TIMEOUT,
            ScopeInterface::SCOPE_STORE,
            $storeId
        ));
    }

    public function getReadTimeoutSeconds(?int $storeId = null): int
    {
        return max(1, (int) $this->scopeConfig->getValue(
            self::XML_PATH_READ_TIMEOUT,
            ScopeInterface::SCOPE_STORE,
            $storeId
        ));
    }

    public function isStockReservationEnabled(?int $storeId = null): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_STOCK_RESERVATION_ENABLED,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    public function getStockReservationUrl(?int $storeId = null): string
    {
        return (string) $this->scopeConfig->getValue(
            self::XML_PATH_STOCK_RESERVATION_URL,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    public function getStockReservationConnectTimeoutSeconds(?int $storeId = null): int
    {
        return max(1, (int) $this->scopeConfig->getValue(
            self::XML_PATH_STOCK_RESERVATION_CONNECT_TIMEOUT,
            ScopeInterface::SCOPE_STORE,
            $storeId
        ));
    }

    public function getStockReservationReadTimeoutSeconds(?int $storeId = null): int
    {
        return max(1, (int) $this->scopeConfig->getValue(
            self::XML_PATH_STOCK_RESERVATION_READ_TIMEOUT,
            ScopeInterface::SCOPE_STORE,
            $storeId
        ));
    }

    public function shouldBlockCheckoutOnStockReservationFailure(?int $storeId = null): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_STOCK_RESERVATION_BLOCK_CHECKOUT,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }
}

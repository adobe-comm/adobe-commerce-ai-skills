<?php
declare(strict_types=1);

namespace Brainvire\ErpSync\Model\Api;

use Brainvire\ErpSync\Model\Config;
use Magento\Framework\HTTP\Client\Curl;
use Magento\Framework\Serialize\Serializer\Json;

class ErpStockReservationClient
{
    public function __construct(
        private readonly Config $config,
        private readonly Curl $curl,
        private readonly Json $json
    ) {
    }

    /**
     * @param array<string, mixed> $payload
     * @throws ExportRetryableException
     * @throws ExportPermanentFailureException
     */
    public function postReservationPayload(array $payload, ?int $storeId = null): void
    {
        $url = $this->config->getStockReservationUrl($storeId);
        if ($url === '') {
            throw new ExportPermanentFailureException(
                __('ERP stock reservation URL is not configured (brainvire_erp/stock_reservation/reservation_url).')
            );
        }

        $connectTimeout = $this->config->getStockReservationConnectTimeoutSeconds($storeId);
        $readTimeout = $this->config->getStockReservationReadTimeoutSeconds($storeId);

        $this->curl->setTimeout($readTimeout);
        $this->curl->setOption(CURLOPT_CONNECTTIMEOUT, $connectTimeout);

        try {
            $this->curl->addHeader('Content-Type', 'application/json');
            $this->curl->post($url, $this->json->serialize($payload));
        } catch (\Throwable $exception) {
            throw new ExportRetryableException(
                __('ERP stock reservation request failed: %1', $exception->getMessage()),
                $exception
            );
        }

        $status = (int) $this->curl->getStatus();
        if ($status >= 200 && $status < 300) {
            return;
        }

        if ($status >= 500 || $status === 429) {
            throw new ExportRetryableException(
                __('ERP stock reservation returned retryable HTTP status %1.', $status)
            );
        }

        throw new ExportPermanentFailureException(
            __('ERP stock reservation rejected the request (HTTP %1).', $status)
        );
    }
}

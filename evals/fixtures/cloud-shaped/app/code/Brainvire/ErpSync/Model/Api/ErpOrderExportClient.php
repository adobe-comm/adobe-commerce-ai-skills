<?php
declare(strict_types=1);

namespace Brainvire\ErpSync\Model\Api;

use Brainvire\ErpSync\Model\Config;
use Magento\Framework\HTTP\Client\Curl;
use Magento\Framework\Serialize\Serializer\Json;

class ErpOrderExportClient
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
    public function postOrderPayload(array $payload, ?int $storeId = null): void
    {
        $url = $this->config->getSandboxUrl($storeId);
        if ($url === '') {
            throw new ExportPermanentFailureException(
                __('ERP sandbox URL is not configured (brainvire_erp/order_export/sandbox_url).')
            );
        }

        $apiKey = $this->config->getApiKey($storeId);
        if ($apiKey === '') {
            throw new ExportPermanentFailureException(
                __('ERP API key is not configured (brainvire_erp/order_export/api_key).')
            );
        }

        $connectTimeout = $this->config->getConnectTimeoutSeconds($storeId);
        $readTimeout = $this->config->getReadTimeoutSeconds($storeId);

        $this->curl->setTimeout($readTimeout);
        $this->curl->setOption(CURLOPT_CONNECTTIMEOUT, $connectTimeout);

        try {
            $this->curl->addHeader('Content-Type', 'application/json');
            $this->curl->addHeader('Authorization', 'Bearer ' . $apiKey);
            $this->curl->post($url, $this->json->serialize($payload));
        } catch (\Throwable $exception) {
            throw new ExportRetryableException(
                __('ERP order export request failed: %1', $exception->getMessage()),
                $exception
            );
        }

        $status = (int) $this->curl->getStatus();
        if ($status >= 200 && $status < 300) {
            return;
        }

        if ($status >= 500 || $status === 429) {
            throw new ExportRetryableException(
                __('ERP order export returned retryable HTTP status %1.', $status)
            );
        }

        throw new ExportPermanentFailureException(
            __('ERP order export rejected the payload (HTTP %1).', $status)
        );
    }
}

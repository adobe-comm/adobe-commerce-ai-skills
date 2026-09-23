<?php
declare(strict_types=1);

namespace Brainvire\ErpSync\Model;

use Brainvire\ErpSync\Model\Api\ExportPermanentFailureException;
use Brainvire\ErpSync\Model\Api\ExportRetryableException;
use Brainvire\ErpSync\Model\Export\FailedExportRegistry;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Serialize\Serializer\Json;
use Psr\Log\LoggerInterface;

class OrderExportConsumer
{
    public function __construct(
        private readonly OrderExportService $orderExportService,
        private readonly FailedExportRegistry $failedExportRegistry,
        private readonly Json $json,
        private readonly LoggerInterface $logger
    ) {
    }

    public function process(string $message): void
    {
        $orderId = $this->extractOrderId($message);
        if ($orderId === null) {
            $this->logger->error('ERP order export queue message is invalid', [
                'message' => $message,
            ]);
            return;
        }

        try {
            $this->orderExportService->exportByOrderId($orderId);
        } catch (NoSuchEntityException $exception) {
            $this->logger->error('ERP order export skipped: order not found', [
                'order_id' => $orderId,
                'message' => $exception->getMessage(),
            ]);
        } catch (ExportPermanentFailureException $exception) {
            $this->logger->error('ERP order export permanent failure', [
                'order_id' => $orderId,
                'message' => $exception->getMessage(),
            ]);
        } catch (ExportRetryableException $exception) {
            // In-process retries exhausted; ack the message and defer to the hourly cron retry job.
            $this->failedExportRegistry->markFailed($orderId);
            $this->logger->critical('ERP order export exhausted retries; queued for cron retry', [
                'order_id' => $orderId,
                'message' => $exception->getMessage(),
            ]);
        }
    }

    private function extractOrderId(string $message): ?int
    {
        try {
            $data = $this->json->unserialize($message);
        } catch (\InvalidArgumentException) {
            return null;
        }

        if (!is_array($data) || !isset($data['order_id'])) {
            return null;
        }

        $orderId = (int) $data['order_id'];
        return $orderId > 0 ? $orderId : null;
    }
}

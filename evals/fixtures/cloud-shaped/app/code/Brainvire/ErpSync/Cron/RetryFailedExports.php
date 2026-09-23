<?php
declare(strict_types=1);

namespace Brainvire\ErpSync\Cron;

use Brainvire\ErpSync\Model\Api\ExportPermanentFailureException;
use Brainvire\ErpSync\Model\Api\ExportRetryableException;
use Brainvire\ErpSync\Model\Export\FailedExportRegistry;
use Brainvire\ErpSync\Model\OrderExportService;
use Magento\Framework\Exception\NoSuchEntityException;
use Psr\Log\LoggerInterface;

class RetryFailedExports
{
    public function __construct(
        private readonly FailedExportRegistry $failedExportRegistry,
        private readonly OrderExportService $orderExportService,
        private readonly LoggerInterface $logger
    ) {
    }

    public function execute(): void
    {
        foreach ($this->failedExportRegistry->listOrderIds() as $orderId) {
            try {
                $this->orderExportService->exportByOrderId($orderId);
            } catch (NoSuchEntityException $exception) {
                $this->failedExportRegistry->clear($orderId);
                $this->logger->error('ERP order export retry skipped: order not found', [
                    'order_id' => $orderId,
                    'message' => $exception->getMessage(),
                ]);
                continue;
            } catch (ExportPermanentFailureException $exception) {
                $this->failedExportRegistry->clear($orderId);
                $this->logger->error('ERP order export retry permanent failure', [
                    'order_id' => $orderId,
                    'message' => $exception->getMessage(),
                ]);
                continue;
            } catch (ExportRetryableException $exception) {
                $this->logger->warning('ERP order export retry deferred: ERP still unavailable', [
                    'order_id' => $orderId,
                    'message' => $exception->getMessage(),
                ]);
                continue;
            }

            $this->failedExportRegistry->clear($orderId);
            $this->logger->info('ERP order export retry succeeded', [
                'order_id' => $orderId,
            ]);
        }
    }
}

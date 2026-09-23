<?php
declare(strict_types=1);

namespace Brainvire\ErpSync\Model\Export;

use Brainvire\ErpSync\Model\Api\ExportPermanentFailureException;
use Brainvire\ErpSync\Model\Api\ExportRetryableException;
use Brainvire\ErpSync\Model\Config;
use Psr\Log\LoggerInterface;

class RetryExecutor
{
    public function __construct(
        private readonly Config $config,
        private readonly LoggerInterface $logger
    ) {
    }

    /**
     * Runs an ERP export attempt with exponential backoff on transient failures.
     *
     * @throws ExportPermanentFailureException
     * @throws ExportRetryableException when all attempts are exhausted
     */
    public function run(callable $operation, int $orderId, ?int $storeId = null): void
    {
        $maxAttempts = $this->config->getMaxAttempts($storeId);
        $delaySeconds = $this->config->getInitialDelaySeconds($storeId);
        $maxDelaySeconds = $this->config->getMaxDelaySeconds($storeId);

        for ($attempt = 1; $attempt <= $maxAttempts; $attempt++) {
            try {
                $operation();
                if ($attempt > 1) {
                    $this->logger->info('ERP order export succeeded after retry', [
                        'order_id' => $orderId,
                        'attempt' => $attempt,
                    ]);
                }
                return;
            } catch (ExportPermanentFailureException $exception) {
                $this->logger->error('ERP order export permanent failure', [
                    'order_id' => $orderId,
                    'attempt' => $attempt,
                    'message' => $exception->getMessage(),
                ]);
                throw $exception;
            } catch (ExportRetryableException $exception) {
                if ($attempt >= $maxAttempts) {
                    $this->logger->error('ERP order export failed after max retries', [
                        'order_id' => $orderId,
                        'attempt' => $attempt,
                        'max_attempts' => $maxAttempts,
                        'message' => $exception->getMessage(),
                    ]);
                    throw $exception;
                }

                $this->logger->warning('ERP order export retry scheduled', [
                    'order_id' => $orderId,
                    'attempt' => $attempt,
                    'next_delay_seconds' => $delaySeconds,
                ]);

                if ($delaySeconds > 0) {
                    sleep($delaySeconds);
                }

                $delaySeconds = min($delaySeconds * 2, $maxDelaySeconds);
            }
        }
    }
}

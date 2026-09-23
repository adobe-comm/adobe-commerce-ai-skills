<?php
declare(strict_types=1);

namespace Brainvire\ErpSync\Model;

use Brainvire\ErpSync\Model\Api\ErpStockReservationClient;
use Brainvire\ErpSync\Model\Api\ExportPermanentFailureException;
use Brainvire\ErpSync\Model\Api\ExportRetryableException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\Data\OrderItemInterface;
use Psr\Log\LoggerInterface;

class StockReservationService
{
    public function __construct(
        private readonly Config $config,
        private readonly ErpStockReservationClient $reservationClient,
        private readonly LoggerInterface $logger
    ) {
    }

    /**
     * Synchronous ERP stock reservation on the place-order path (single attempt, configured timeouts).
     *
     * @throws CouldNotSaveException when reservation fails and block_checkout_on_failure is enabled
     */
    public function reserveForOrder(OrderInterface $order): void
    {
        $storeId = (int) $order->getStoreId();
        if (!$this->config->isStockReservationEnabled($storeId)) {
            return;
        }

        $payload = $this->buildPayload($order);
        $orderId = (int) $order->getEntityId();
        $incrementId = (string) $order->getIncrementId();

        try {
            $this->reservationClient->postReservationPayload($payload, $storeId);
            $this->logger->info('ERP stock reserved for order', [
                'order_id' => $orderId,
                'increment_id' => $incrementId,
                'correlation_id' => $payload['idempotency_key'],
            ]);
        } catch (ExportPermanentFailureException | ExportRetryableException $exception) {
            $this->logger->error('ERP stock reservation failed during place order', [
                'order_id' => $orderId,
                'increment_id' => $incrementId,
                'correlation_id' => $payload['idempotency_key'],
                'message' => $exception->getMessage(),
            ]);

            if ($this->config->shouldBlockCheckoutOnStockReservationFailure($storeId)) {
                throw new CouldNotSaveException(
                    __(
                        'We could not confirm stock with our warehouse. Please try again or contact support. (Order %1)',
                        $incrementId !== '' ? $incrementId : (string) $orderId
                    ),
                    $exception
                );
            }

            $this->logger->warning(
                'ERP stock reservation failed but checkout was allowed (block_checkout_on_failure=0)',
                [
                    'order_id' => $orderId,
                    'increment_id' => $incrementId,
                    'correlation_id' => $payload['idempotency_key'],
                ]
            );
        }
    }

    /**
     * @return array<string, mixed>
     */
    private function buildPayload(OrderInterface $order): array
    {
        $items = [];
        foreach ($order->getAllVisibleItems() as $item) {
            if (!$item instanceof OrderItemInterface) {
                continue;
            }
            $items[] = [
                'sku' => (string) $item->getSku(),
                'qty' => (float) $item->getQtyOrdered(),
            ];
        }

        $incrementId = (string) $order->getIncrementId();

        return [
            'idempotency_key' => sprintf('magento-stock-reservation-%s', $incrementId),
            'order_id' => (int) $order->getEntityId(),
            'increment_id' => $incrementId,
            'items' => $items,
        ];
    }
}

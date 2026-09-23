<?php
declare(strict_types=1);

namespace Brainvire\ErpSync\Model;

use Brainvire\ErpSync\Model\Api\ErpOrderExportClient;
use Brainvire\ErpSync\Model\Api\ExportPermanentFailureException;
use Brainvire\ErpSync\Model\Api\ExportRetryableException;
use Brainvire\ErpSync\Model\Export\RetryExecutor;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\Data\OrderItemInterface;
use Magento\Sales\Api\OrderRepositoryInterface;

class OrderExportService
{
    public function __construct(
        private readonly OrderRepositoryInterface $orderRepository,
        private readonly ErpOrderExportClient $exportClient,
        private readonly RetryExecutor $retryExecutor
    ) {
    }

    /**
     * @throws ExportPermanentFailureException
     * @throws ExportRetryableException
     * @throws NoSuchEntityException
     */
    public function exportByOrderId(int $orderId): void
    {
        $order = $this->orderRepository->get($orderId);
        $storeId = (int) $order->getStoreId();
        $payload = $this->buildPayload($order);

        $this->retryExecutor->run(
            function () use ($payload, $storeId): void {
                $this->exportClient->postOrderPayload($payload, $storeId);
            },
            $orderId,
            $storeId
        );
    }

    /**
     * @return array<string, mixed>
     */
    private function buildPayload(OrderInterface $order): array
    {
        $lineItems = [];
        foreach ($order->getAllVisibleItems() as $item) {
            if (!$item instanceof OrderItemInterface) {
                continue;
            }
            $lineItems[] = [
                'sku' => (string) $item->getSku(),
                'qty' => (float) $item->getQtyOrdered(),
                'discount_amount' => (float) $item->getDiscountAmount(),
            ];
        }

        return [
            'idempotency_key' => sprintf('magento-order-%s', $order->getIncrementId()),
            'order_id' => (int) $order->getEntityId(),
            'increment_id' => (string) $order->getIncrementId(),
            'grand_total' => (float) $order->getGrandTotal(),
            'discount_amount' => (float) $order->getDiscountAmount(),
            'currency' => (string) $order->getOrderCurrencyCode(),
            'line_items' => $lineItems,
        ];
    }
}

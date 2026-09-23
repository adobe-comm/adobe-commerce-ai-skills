<?php
declare(strict_types=1);

namespace Brainvire\ErpSync\Model\Queue;

use Brainvire\ErpSync\Model\Export\FailedExportRegistry;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\MessageQueue\PublisherInterface;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Sales\Api\OrderRepositoryInterface;

class OrderExportQueuePublisher
{
    public const TOPIC_NAME = 'brainvire.erp.order.export';

    public function __construct(
        private readonly PublisherInterface $publisher,
        private readonly OrderRepositoryInterface $orderRepository,
        private readonly FailedExportRegistry $failedExportRegistry,
        private readonly Json $json
    ) {
    }

    /**
     * @throws NoSuchEntityException
     */
    public function enqueueByOrderId(int $orderId): void
    {
        if ($orderId <= 0) {
            throw new NoSuchEntityException(__('Invalid order ID.'));
        }

        $this->orderRepository->get($orderId);

        $message = $this->json->serialize(['order_id' => $orderId]);
        $this->publisher->publish(self::TOPIC_NAME, $message);
        $this->failedExportRegistry->clear($orderId);
    }
}

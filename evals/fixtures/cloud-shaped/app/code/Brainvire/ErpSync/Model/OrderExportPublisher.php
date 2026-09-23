<?php
declare(strict_types=1);

namespace Brainvire\ErpSync\Model;

use Magento\Framework\MessageQueue\PublisherInterface;
use Magento\Framework\Serialize\Serializer\Json;

class OrderExportPublisher
{
    public const TOPIC = 'brainvire.erp.order.export';

    public function __construct(
        private readonly PublisherInterface $publisher,
        private readonly Json $json
    ) {
    }

    public function enqueue(int $orderId): void
    {
        if ($orderId <= 0) {
            throw new \InvalidArgumentException('Order ID must be a positive integer.');
        }

        $this->publisher->publish(
            self::TOPIC,
            $this->json->serialize(['order_id' => $orderId])
        );
    }
}

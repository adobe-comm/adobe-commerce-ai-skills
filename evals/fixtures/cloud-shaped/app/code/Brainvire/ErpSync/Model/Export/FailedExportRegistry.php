<?php
declare(strict_types=1);

namespace Brainvire\ErpSync\Model\Export;

use Magento\Framework\FlagManager;
use Magento\Framework\Serialize\Serializer\Json;

/**
 * Tracks order exports that exhausted in-process retries for later cron replay.
 */
class FailedExportRegistry
{
    private const FLAG_CODE = 'brainvire_erp_failed_order_exports';

    public function __construct(
        private readonly FlagManager $flagManager,
        private readonly Json $json
    ) {
    }

    /**
     * @return list<int>
     */
    public function listOrderIds(): array
    {
        $raw = $this->flagManager->getFlagData(self::FLAG_CODE);
        if (!is_string($raw) || $raw === '') {
            return [];
        }

        try {
            $decoded = $this->json->unserialize($raw);
        } catch (\InvalidArgumentException) {
            return [];
        }

        if (!is_array($decoded)) {
            return [];
        }

        $orderIds = [];
        foreach ($decoded as $value) {
            $orderId = (int) $value;
            if ($orderId > 0) {
                $orderIds[$orderId] = $orderId;
            }
        }

        return array_values($orderIds);
    }

    public function markFailed(int $orderId): void
    {
        if ($orderId <= 0) {
            return;
        }

        $orderIds = $this->listOrderIds();
        if (in_array($orderId, $orderIds, true)) {
            return;
        }

        $orderIds[] = $orderId;
        $this->persist($orderIds);
    }

    public function clear(int $orderId): void
    {
        if ($orderId <= 0) {
            return;
        }

        $orderIds = array_values(array_filter(
            $this->listOrderIds(),
            static fn (int $id): bool => $id !== $orderId
        ));

        $this->persist($orderIds);
    }

    /**
     * @param list<int> $orderIds
     */
    private function persist(array $orderIds): void
    {
        $this->flagManager->saveFlag(
            self::FLAG_CODE,
            $this->json->serialize(array_values($orderIds))
        );
    }
}

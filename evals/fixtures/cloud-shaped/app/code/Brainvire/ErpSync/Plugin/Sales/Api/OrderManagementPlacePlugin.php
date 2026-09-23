<?php
declare(strict_types=1);

namespace Brainvire\ErpSync\Plugin\Sales\Api;

use Brainvire\ErpSync\Model\StockReservationService;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\OrderManagementInterface;

class OrderManagementPlacePlugin
{
    public function __construct(
        private readonly StockReservationService $stockReservationService
    ) {
    }

    /**
     * Reserve stock in the ERP before the order is persisted (synchronous, bounded timeouts).
     */
    public function beforePlace(
        OrderManagementInterface $subject,
        OrderInterface $order
    ): void {
        $this->stockReservationService->reserveForOrder($order);
    }
}

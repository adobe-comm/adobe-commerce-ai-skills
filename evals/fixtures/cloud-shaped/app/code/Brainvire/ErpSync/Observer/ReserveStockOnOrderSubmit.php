<?php
declare(strict_types=1);

namespace Brainvire\ErpSync\Observer;

use Brainvire\ErpSync\Model\StockReservationService;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Sales\Api\Data\OrderInterface;

class ReserveStockOnOrderSubmit implements ObserverInterface
{
    public function __construct(
        private readonly StockReservationService $stockReservationService
    ) {
    }

    public function execute(Observer $observer): void
    {
        $order = $observer->getEvent()->getOrder();
        if (!$order instanceof OrderInterface) {
            return;
        }

        $this->stockReservationService->reserveForOrder($order);
    }
}

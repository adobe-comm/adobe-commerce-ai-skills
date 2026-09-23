<?php
declare(strict_types=1);

namespace Brainvire\CompanyOrders\Plugin\Sales\Block\Order;

use Brainvire\CompanyOrders\Model\BuyerOrderLoader;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\Exception\LocalizedException;
use Magento\Sales\Block\Order\History;
use Magento\Sales\Model\ResourceModel\Order\Collection;

class HistoryPlugin
{
    public function __construct(
        private readonly BuyerOrderLoader $buyerOrderLoader,
        private readonly CustomerSession $customerSession,
    ) {
    }

    /**
     * Use company-scoped order collection for B2B company users (Magento_Company ACL).
     */
    public function aroundGetOrders(History $subject, callable $proceed): Collection
    {
        $customerId = (int) $this->customerSession->getCustomerId();
        if ($customerId <= 0) {
            return $proceed();
        }

        $customer = $this->customerSession->getCustomerDataObject();
        if ($customer === null) {
            return $proceed();
        }

        try {
            return $this->buyerOrderLoader->getOrdersForCustomer($customer);
        } catch (LocalizedException) {
            return $proceed();
        }
    }
}

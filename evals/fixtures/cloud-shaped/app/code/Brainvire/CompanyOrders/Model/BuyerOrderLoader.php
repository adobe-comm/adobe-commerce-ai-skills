<?php
declare(strict_types=1);

namespace Brainvire\CompanyOrders\Model;

use Magento\Company\Api\CompanyManagementInterface;
use Magento\Company\Model\Authorization;
use Magento\Company\Model\ResourceModel\Order\CollectionFactory as CompanyOrderCollectionFactory;
use Magento\Company\Model\ResourceModel\Order\Collection as CompanyOrderCollection;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Framework\Exception\LocalizedException;

/**
 * Loads storefront order history scoped to the logged-in company user.
 *
 * Previously this project used a plain sales order collection filtered only by store,
 * which exposed other companies' orders to company buyers on shared websites.
 */
class BuyerOrderLoader
{
    private const PERMISSION_VIEW_ALL_COMPANY_ORDERS = 'Magento_Sales::view_all_company_orders';

    public function __construct(
        private readonly CompanyManagementInterface $companyManagement,
        private readonly CompanyOrderCollectionFactory $companyOrderCollectionFactory,
        private readonly Authorization $companyAuthorization
    ) {
    }

    /**
     * @throws LocalizedException
     */
    public function getOrdersForCustomer(CustomerInterface $customer): CompanyOrderCollection
    {
        $company = $this->companyManagement->getByCustomerId((int) $customer->getId());
        if (!$company || !(int) $company->getId()) {
            throw new LocalizedException(__('Company account is required to view orders.'));
        }

        $collection = $this->companyOrderCollectionFactory->create();
        $collection->addCompanyFilter((int) $company->getId());

        if (!$this->companyAuthorization->isAllowed(self::PERMISSION_VIEW_ALL_COMPANY_ORDERS)) {
            $collection->addFieldToFilter('customer_id', (int) $customer->getId());
        }

        return $collection;
    }
}

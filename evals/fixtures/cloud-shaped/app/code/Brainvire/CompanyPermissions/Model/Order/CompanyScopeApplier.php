<?php
declare(strict_types=1);

namespace Brainvire\CompanyPermissions\Model\Order;

use Magento\Company\Model\CompanyContext;
use Magento\Sales\Model\ResourceModel\Order\Collection;

/**
 * Restricts storefront order collections to the logged-in company user's company.
 *
 * Previously scoped only by store_id, so company buyers could see other companies' orders
 * on the same website. Company ACL roles (Magento_Company) still apply on top of this scope.
 */
class CompanyScopeApplier
{
    private const FLAG_APPLIED = 'brainvire_company_scope_applied';

    public function __construct(
        private readonly CompanyContext $companyContext,
    ) {
    }

    public function apply(Collection $collection): void
    {
        if (!$this->companyContext->isModuleActive()
            || !$this->companyContext->isCustomerLoggedIn()
            || $collection->getFlag(self::FLAG_APPLIED)
        ) {
            return;
        }

        $companyId = (int) $this->companyContext->getCompanyId();
        if ($companyId <= 0) {
            return;
        }

        $collection->getSelect()->joinInner(
            ['brainvire_company_order' => $collection->getTable('company_order')],
            'main_table.entity_id = brainvire_company_order.order_id',
            []
        )->where('brainvire_company_order.company_id = ?', $companyId);

        $collection->setFlag(self::FLAG_APPLIED, true);
    }
}

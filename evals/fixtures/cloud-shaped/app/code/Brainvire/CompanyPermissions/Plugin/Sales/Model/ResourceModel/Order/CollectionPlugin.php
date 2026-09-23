<?php
declare(strict_types=1);

namespace Brainvire\CompanyPermissions\Plugin\Sales\Model\ResourceModel\Order;

use Brainvire\CompanyPermissions\Model\Order\CompanyScopeApplier;
use Magento\Framework\App\State;
use Magento\Framework\App\Area;
use Magento\Sales\Model\ResourceModel\Order\Collection;

class CollectionPlugin
{
    public function __construct(
        private readonly CompanyScopeApplier $companyScopeApplier,
        private readonly State $appState,
    ) {
    }

    /**
     * @param mixed $printQuery
     * @param mixed $logQuery
     * @return array{0: mixed, 1: mixed}
     */
    public function beforeLoad(Collection $subject, $printQuery = false, $logQuery = false): array
    {
        try {
            if ($this->appState->getAreaCode() === Area::AREA_FRONTEND) {
                $this->companyScopeApplier->apply($subject);
            }
        } catch (\Magento\Framework\Exception\LocalizedException) {
            // Area not set yet — skip scoping (admin/cron collections unchanged).
        }

        return [$printQuery, $logQuery];
    }
}

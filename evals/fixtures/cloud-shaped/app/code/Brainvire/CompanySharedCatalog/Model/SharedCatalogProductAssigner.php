<?php
declare(strict_types=1);

namespace Brainvire\CompanySharedCatalog\Model;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Company\Api\CompanyRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\SharedCatalog\Api\Data\ProductItemInterface;
use Magento\SharedCatalog\Api\Data\ProductItemInterfaceFactory;
use Magento\SharedCatalog\Api\ProductItemManagementInterface;
use Magento\SharedCatalog\Api\SharedCatalogRepositoryInterface;

/**
 * Adds a SKU to the Magento_SharedCatalog assigned to a B2B company with an optional custom price.
 */
class SharedCatalogProductAssigner
{
    public function __construct(
        private readonly CompanyRepositoryInterface $companyRepository,
        private readonly SharedCatalogRepositoryInterface $sharedCatalogRepository,
        private readonly SearchCriteriaBuilder $searchCriteriaBuilder,
        private readonly ProductRepositoryInterface $productRepository,
        private readonly ProductItemInterfaceFactory $productItemFactory,
        private readonly ProductItemManagementInterface $productItemManagement,
    ) {
    }

    /**
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function assign(int $companyId, string $sku, ?float $customPrice = null): void
    {
        $sku = trim($sku);
        if ($sku === '') {
            throw new LocalizedException(__('Product SKU is required.'));
        }

        $this->productRepository->get($sku);

        $company = $this->companyRepository->get($companyId);
        $customerGroupId = (int) $company->getCustomerGroupId();
        if ($customerGroupId <= 0) {
            throw new LocalizedException(__('Company %1 has no customer group.', $companyId));
        }

        $sharedCatalogId = $this->resolveSharedCatalogId($customerGroupId);
        if ($sharedCatalogId === null) {
            throw new LocalizedException(
                __(
                    'No shared catalog is assigned to company %1 (customer group %2).',
                    $companyId,
                    $customerGroupId
                )
            );
        }

        /** @var ProductItemInterface $productItem */
        $productItem = $this->productItemFactory->create();
        $productItem->setSku($sku);
        if ($customPrice !== null) {
            $productItem->setPrice($customPrice);
        }

        $this->productItemManagement->assignProducts($sharedCatalogId, [$productItem]);
    }

    private function resolveSharedCatalogId(int $customerGroupId): ?int
    {
        $criteria = $this->searchCriteriaBuilder
            ->addFilter('customer_group_id', $customerGroupId)
            ->setPageSize(1)
            ->create();

        $items = $this->sharedCatalogRepository->getList($criteria)->getItems();
        $sharedCatalog = reset($items);

        return $sharedCatalog ? (int) $sharedCatalog->getId() : null;
    }
}

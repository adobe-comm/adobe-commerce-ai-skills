<?php
declare(strict_types=1);

namespace Brainvire\CompanySharedCatalog\Model;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Company\Api\CompanyRepositoryInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\SharedCatalog\Api\Data\ProductItemInterfaceFactory;
use Magento\SharedCatalog\Api\ProductItemManagementInterface;
use Magento\SharedCatalog\Model\ResourceModel\SharedCatalog\CollectionFactory as SharedCatalogCollectionFactory;

/**
 * Assigns a catalog SKU (with optional custom price) to the shared catalog linked to one company.
 *
 * B2B shared-catalog pricing is scoped by company customer group — not B2C catalog price rules.
 */
class CompanyProductPricingAssigner
{
    public function __construct(
        private readonly CompanyRepositoryInterface $companyRepository,
        private readonly SharedCatalogCollectionFactory $sharedCatalogCollectionFactory,
        private readonly ProductRepositoryInterface $productRepository,
        private readonly ProductItemInterfaceFactory $productItemFactory,
        private readonly ProductItemManagementInterface $productItemManagement,
    ) {
    }

    /**
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function assignProduct(int $companyId, string $sku, ?float $customPrice = null): void
    {
        if ($companyId <= 0) {
            throw new LocalizedException(__('A valid company ID is required.'));
        }

        $sku = trim($sku);
        if ($sku === '') {
            throw new LocalizedException(__('Product SKU is required.'));
        }

        $company = $this->companyRepository->get($companyId);
        $sharedCatalogId = $this->resolveSharedCatalogId((int) $company->getCustomerGroupId());

        $this->productRepository->get($sku);

        $productItem = $this->productItemFactory->create();
        $productItem->setSku($sku);
        if ($customPrice !== null) {
            $productItem->setPrice($customPrice);
        }

        $this->productItemManagement->assignProducts($sharedCatalogId, [$productItem]);
    }

    /**
     * @throws LocalizedException
     */
    private function resolveSharedCatalogId(int $customerGroupId): int
    {
        if ($customerGroupId <= 0) {
            throw new LocalizedException(__('Company is not linked to a B2B customer group.'));
        }

        $collection = $this->sharedCatalogCollectionFactory->create();
        $collection->addFieldToFilter('customer_group_id', $customerGroupId);
        $sharedCatalog = $collection->getFirstItem();

        $sharedCatalogId = (int) $sharedCatalog->getId();
        if ($sharedCatalogId <= 0) {
            throw new LocalizedException(__('No shared catalog is assigned to this company.'));
        }

        return $sharedCatalogId;
    }
}

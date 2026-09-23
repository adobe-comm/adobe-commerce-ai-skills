<?php
declare(strict_types=1);

namespace Brainvire\PickupPointsShipping\Model\Carrier;

use Brainvire\PickupPointsShipping\Model\ResourceModel\PickupPoint\CollectionFactory;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Quote\Model\Quote\Address\RateRequest;
use Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory;
use Magento\Quote\Model\Quote\Address\RateResult\MethodFactory;
use Magento\Shipping\Model\Carrier\AbstractCarrier;
use Magento\Shipping\Model\Carrier\CarrierInterface;
use Magento\Shipping\Model\Rate\Result;
use Magento\Shipping\Model\Rate\ResultFactory;
use Psr\Log\LoggerInterface;

class PickupPoints extends AbstractCarrier implements CarrierInterface
{
    public const CODE = 'pickuppoints';

    protected $_code = self::CODE;

    public function __construct(
        ScopeConfigInterface $scopeConfig,
        ErrorFactory $rateErrorFactory,
        LoggerInterface $logger,
        private readonly ResultFactory $rateResultFactory,
        private readonly MethodFactory $rateMethodFactory,
        private readonly CollectionFactory $pickupPointCollectionFactory,
        array $data = []
    ) {
        parent::__construct($scopeConfig, $rateErrorFactory, $logger, $data);
    }

    /**
     * @return Result|bool
     */
    public function collectRates(RateRequest $request)
    {
        if (!$this->getConfigFlag('active')) {
            return false;
        }

        $points = $this->pickupPointCollectionFactory->create()
            ->addFieldToFilter('is_active', 1)
            ->setOrder('sort_order', 'ASC');

        if ($points->getSize() === 0) {
            return false;
        }

        /** @var Result $result */
        $result = $this->rateResultFactory->create();
        $price = (float) $this->getConfigData('price');

        foreach ($points as $point) {
            $method = $this->rateMethodFactory->create();
            $method->setCarrier($this->_code);
            $method->setCarrierTitle((string) $this->getConfigData('title'));
            $method->setMethod($this->buildMethodCode((int) $point->getId()));
            $method->setMethodTitle($this->formatMethodTitle($point));
            $method->setPrice($price);
            $method->setCost($price);
            $result->append($method);
        }

        return $result;
    }

    public function getAllowedMethods(): array
    {
        $methods = [];
        $points = $this->pickupPointCollectionFactory->create()
            ->addFieldToFilter('is_active', 1)
            ->setOrder('sort_order', 'ASC');

        foreach ($points as $point) {
            $code = $this->buildMethodCode((int) $point->getId());
            $methods[$code] = $this->formatMethodTitle($point);
        }

        return $methods;
    }

    public static function buildMethodCode(int $pickupPointId): string
    {
        return 'point_' . $pickupPointId;
    }

    public static function extractPickupPointId(?string $shippingMethod): ?int
    {
        if ($shippingMethod === null || $shippingMethod === '') {
            return null;
        }

        $prefix = self::CODE . '_';
        if (!str_starts_with($shippingMethod, $prefix)) {
            return null;
        }

        $methodPart = substr($shippingMethod, strlen($prefix));
        if (!str_starts_with($methodPart, 'point_')) {
            return null;
        }

        $id = (int) substr($methodPart, strlen('point_'));

        return $id > 0 ? $id : null;
    }

    private function formatMethodTitle(\Brainvire\PickupPointsShipping\Model\PickupPoint $point): string
    {
        return sprintf(
            '%s — %s, %s %s',
            (string) $point->getData('name'),
            (string) $point->getData('street'),
            (string) $point->getData('city'),
            (string) $point->getData('postcode')
        );
    }
}

<?php
declare(strict_types=1);

namespace Brainvire\CheckoutFee\Plugin\Model;

use Magento\Checkout\Model\Cart;
use Psr\Log\LoggerInterface;

class CartAddProductPlugin
{
    public function __construct(
        private readonly LoggerInterface $logger
    ) {
    }

    /**
     * @param mixed $result
     * @param mixed $productInfo
     * @param mixed $requestInfo
     * @return mixed
     */
    public function afterAddProduct(
        Cart $subject,
        $result,
        $productInfo,
        $requestInfo = null
    ) {
        $this->logger->info('Product added to cart via checkout cart model', [
            'quote_id' => $subject->getQuote()->getId(),
            'product_info' => is_object($productInfo) ? $productInfo->getId() : $productInfo,
        ]);

        return $result;
    }
}

<?php
declare(strict_types=1);

namespace Brainvire\CartAddLog\Plugin\Checkout\Model;

use Magento\Catalog\Model\Product;
use Magento\Checkout\Model\Cart;
use Psr\Log\LoggerInterface;

class CartPlugin
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
    public function afterAddProduct(Cart $subject, $result, $productInfo, $requestInfo = null)
    {
        $productLabel = $productInfo instanceof Product
            ? $productInfo->getSku()
            : (string) $productInfo;

        $this->logger->info('Cart::addProduct completed', [
            'product' => $productLabel,
            'quote_id' => $subject->getQuote()->getId(),
        ]);

        return $result;
    }
}

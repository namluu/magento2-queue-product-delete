<?php
namespace Namluu\Queue\Plugin;

use Namluu\Queue\Model\Product\DeletePublisher;
use Magento\Catalog\Model\ResourceModel\Product as ProductResource;

class ProductDeletePlugin
{
    /**
     * @var \Magento\Quote\Model\Product\QuoteItemsCleanerInterface
     */
    private $productDeletePublisher;

    /**
     * @param \Magento\Quote\Model\Product\QuoteItemsCleanerInterface $quoteItemsCleaner
     */
    public function __construct(DeletePublisher $productDeletePublisher)
    {
        $this->productDeletePublisher = $productDeletePublisher;
    }

    /**
     * @param ProductResource $subject
     * @param ProductResource $result
     * @param \Magento\Catalog\Api\Data\ProductInterface $product
     * @return ProductResource
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterDelete(
        ProductResource $subject,
        ProductResource $result,
        \Magento\Catalog\Api\Data\ProductInterface $product
    ) {
        $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/product-delete.log');
        $logger = new \Zend\Log\Logger();
        $logger->addWriter($writer);
        $logger->info('Begin Delete Product: ' . $product->getId() . ', detail: ' . __FILE__ . __LINE__);
        $this->productDeletePublisher->execute($product);
        return $result;
    }
}

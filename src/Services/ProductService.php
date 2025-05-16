<?php

namespace AcmeWidgetCo\Services;

use AcmeWidgetCo\DataTransferObjects\ProductData;
use AcmeWidgetCo\Foundation\ProductCollection;

class ProductService
{
    /**
     * Get the list of available products.
     *
     * @return ProductCollection
     */
    public function getProducts(): ProductCollection
    {
        return new ProductCollection([
            new ProductData('R01', 'Red Widget', 3295), 
            new ProductData('G01', 'Green Widget', 2495), 
            new ProductData('B01', 'Blue Widget', 795),  
        ]);
    }

    public function findByCode(string $productCode): ?ProductData
    {
        return $this->getProducts()
            ->filter(fn (ProductData $product) => $product->getCode() === $productCode)
            ->first();
    }
}

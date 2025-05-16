<?php

namespace AcmeWidgetCo;

use AcmeWidgetCo\Foundation\ProductCollection;
use AcmeWidgetCo\Services\ProductService;
use AcmeWidgetCo\Services\DeliveryChargeService;
use AcmeWidgetCo\DataTransferObjects\ProductData;
use AcmeWidgetCo\Exceptions\NotFoundException;
use AcmeWidgetCo\Repositories\SpecialOfferRepository;
use AcmeWidgetCo\Services\SpecialOfferDiscountService;

class Basket
{
    public function __construct(
        private ProductCollection $products,
        private DeliveryChargeService $deliveryChargeService,
        private SpecialOfferDiscountService $specialOfferDiscountService,
        private ProductService $productService,
        private SpecialOfferRepository $specialOfferRepository
    ) {}

    public function add(string $productCode): void
    {
        $product = $this->productService->findByCode($productCode);

        if (!$product) {
            throw new NotFoundException($productCode);
        }

        $this->products->add($product);
    }

    public function total(): int
    {
        if ($this->products->isEmpty()) {
            return 0;
        }

        $subtotalInCents       = $this->calculateSubtotal();
        $discountInCents       = $this->calculateDiscount();
        $totalAfterDiscount    = $subtotalInCents - $discountInCents;
        $deliveryChargeInCents = $this->deliveryChargeService->calculate($totalAfterDiscount);

        return $totalAfterDiscount + $deliveryChargeInCents;
    }

    private function calculateSubtotal(): int
    {
        if ($this->products->isEmpty()) {
            return 0;
        }
    
        return array_reduce(
            $this->products->getItems(),
            fn (int $currentSubtotal, ProductData $product) => $currentSubtotal + $product->getPriceInCents(),
            0
        );
    }
    
    private function calculateDiscount(): int
    {
        $activeOffers = $this->specialOfferRepository->getActiveOffers();

        if (empty($activeOffers)) {
            return 0;
        }

        return $this->specialOfferDiscountService->apply($this->products, $activeOffers);
    }
}

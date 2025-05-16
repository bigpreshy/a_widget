<?php

namespace AcmeWidgetCo\Services;

use AcmeWidgetCo\DataTransferObjects\SpecialOfferDetails;
use AcmeWidgetCo\Foundation\ProductCollection;
use AcmeWidgetCo\Factories\DiscountStrategyFactory;

class SpecialOfferDiscountService
{
    public function __construct(
        private DiscountStrategyFactory $strategyFactory
    ) {}

    /**
     * Apply special offers
     * 
     * @param ProductCollection $products
     * @param SpecialOfferDetails[] $specialOffers
     * @return int The total discount amount in cents.
     */
    public function apply(ProductCollection $products, array $specialOffers): int
    {
        $totalDiscountInCents = 0;

        foreach ($specialOffers as $offer) {
            if (!$offer->isActive()) {
                continue;
            }

            // Corrected back: Use make() with the offer object itself
            $strategy = $this->strategyFactory->make($offer); 
            
            // The factory either returns a strategy or throws an exception, so $strategy will always be an object here.
            // Thus, the if ($strategy) check is redundant.
            $discountForOfferInCents = $strategy->apply($products, $offer);

            if ($discountForOfferInCents !== 0) {
                $totalDiscountInCents += $discountForOfferInCents;
            }
        }

        return $totalDiscountInCents;
    }
}

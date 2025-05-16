<?php

namespace AcmeWidgetCo\Strategies;

use AcmeWidgetCo\Interfaces\DiscountStrategyInterface;
use AcmeWidgetCo\DataTransferObjects\ProductData;
use AcmeWidgetCo\DataTransferObjects\SpecialOfferDetails;
use AcmeWidgetCo\Foundation\ProductCollection;

class BuyOneGetOneHalfStrategy implements DiscountStrategyInterface
{
    public function apply(ProductCollection $products, SpecialOfferDetails $offer): int
    {
        // Ensure the offer is valid: it must target at least one product code
        // and require a positive quantity for the offer to trigger.
        if (empty($offer->targetProductCodes[0]) || $offer->quantityRequirement <= 0) {
            return 0;
        }
        $targetProductCode = $offer->targetProductCodes[0];

        // Filter the basket to get a collection of only the products targeted by this offer.
        $eligibleProducts = $products->filter(
            fn (ProductData $product) => $product->getCode() === $targetProductCode
        );

        // If there aren't enough eligible products to meet the offer's quantity requirement,
        // no discount can be applied.
        if ($eligibleProducts->count() < $offer->quantityRequirement) {
            return 0;
        }

        // Calculate how many full "sets" of products are eligible for the discount.
        // For "buy one, get one half price", quantityRequirement is 2.
        // Each set of 2 eligible products yields 1 discounted item.
        // If 3 items are present, 1 set is formed (floor(3/2)=1), not 2 (round(3/2)=2).
        $numberOfDiscountableSets = floor($eligibleProducts->count() / $offer->quantityRequirement);

        // If no full sets are found, no discount.
        if ($numberOfDiscountableSets <= 0) {
            return 0;
        }

        // Assuming all instances of the target product have the same price.
        // Get the price from the first eligible product found.
        /** @var ProductData $firstEligibleProduct */
        $firstEligibleProduct = $eligibleProducts->first();
        // The logic above ensures that $eligibleProducts is not empty and $numberOfDiscountableSets > 0,
        // so $firstEligibleProduct will always be a ProductData object here.
        // The check 'if (!$firstEligibleProduct)' is therefore redundant.

        $productPriceInCents = $firstEligibleProduct->getPriceInCents();

        // Calculate the discount for a single item (50% off, rounding half up).
        $discountPerItemInCents = (int)round($productPriceInCents * 0.5);

        // The total discount is the discount per item multiplied by the number of sets
        // (since each set results in one item being discounted at half price).
        return (int)($discountPerItemInCents * $numberOfDiscountableSets);
    }
}

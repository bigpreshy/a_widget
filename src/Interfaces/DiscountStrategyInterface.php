<?php

namespace AcmeWidgetCo\Interfaces;

use AcmeWidgetCo\DataTransferObjects\SpecialOfferDetails;
use AcmeWidgetCo\Foundation\ProductCollection;

interface DiscountStrategyInterface
{
    public function apply(ProductCollection $products, SpecialOfferDetails $offer): int;
}

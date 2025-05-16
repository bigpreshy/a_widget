<?php

namespace AcmeWidgetCo\Factories;

use AcmeWidgetCo\Interfaces\DiscountStrategyInterface;
use AcmeWidgetCo\DataTransferObjects\SpecialOfferDetails;
use AcmeWidgetCo\Enums\SpecialOffer;
use AcmeWidgetCo\Strategies\BuyOneGetOneHalfStrategy;

class DiscountStrategyFactory
{

    public function make(SpecialOfferDetails $offer): DiscountStrategyInterface
    {
        return match ($offer->type) {
            SpecialOffer::BUY_ONE_GET_ONE_HALF => resolve(BuyOneGetOneHalfStrategy::class),
        };
    }
}

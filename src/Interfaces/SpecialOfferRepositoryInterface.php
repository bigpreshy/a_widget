<?php

namespace AcmeWidgetCo\Interfaces;

use AcmeWidgetCo\DataTransferObjects\SpecialOfferDetails;

interface SpecialOfferRepositoryInterface
{
    /**
     * Get all active special offers.
     *
     * @return SpecialOfferDetails[]
     */
    public function getActiveOffers(): array;
}

<?php

namespace AcmeWidgetCo\Repositories;

use AcmeWidgetCo\Interfaces\SpecialOfferRepositoryInterface;
use AcmeWidgetCo\DataTransferObjects\SpecialOfferDetails;
use AcmeWidgetCo\Enums\SpecialOffer;

class SpecialOfferRepository implements SpecialOfferRepositoryInterface
{
    /**
     * @return SpecialOfferDetails[]
     */
    public function getActiveOffers(): array
    {
        return [
            new SpecialOfferDetails(
                code: 'BOGOH_R01',
                name: 'Buy One Get One Half',
                type: SpecialOffer::BUY_ONE_GET_ONE_HALF,
                targetProductCodes: ['R01'],
                quantityRequirement: 2
            ),
        ];
    }
}

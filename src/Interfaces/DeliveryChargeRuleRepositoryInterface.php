<?php

namespace AcmeWidgetCo\Interfaces;

use AcmeWidgetCo\DataTransferObjects\DeliveryChargeRule;

interface DeliveryChargeRuleRepositoryInterface
{
    /**
     * Fetch all delivery charge rules, sorted by threshold ascending.
     *
     * @return DeliveryChargeRule[]
     */
    public function getAllRulesSortedByThreshold(): array;
}

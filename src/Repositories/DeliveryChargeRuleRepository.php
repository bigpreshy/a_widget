<?php

namespace AcmeWidgetCo\Repositories;

use AcmeWidgetCo\DataTransferObjects\DeliveryChargeRule;
use AcmeWidgetCo\Interfaces\DeliveryChargeRuleRepositoryInterface;

class DeliveryChargeRuleRepository implements DeliveryChargeRuleRepositoryInterface
{
    /** @var DeliveryChargeRule[] */
    private array $rules;

    public function __construct()
    {
        $this->rules = $this->getRulesData();
    }

    /**
     * @return DeliveryChargeRule[]
     */
    private function getRulesData(): array
    {
        return [
            // Threshold in cents, Charge in cents
            new DeliveryChargeRule(0, 495),    // For totals < $50 (threshold 0 cents), charge $4.95
            new DeliveryChargeRule(5000, 295), // For totals < $90 (threshold $50.00 in cents), charge $2.95
            new DeliveryChargeRule(9000, 0),   // For totals >= $90 (threshold $90.00 in cents), charge $0.00
        ];
    }

    /**
     * Get all delivery charge rules, sorted by threshold ascending.
     *
     * @return DeliveryChargeRule[]
     */
    public function getAllRulesSortedByThreshold(): array
    {
        // Sort by threshold ascending to ensure correct delivery charge application.
        usort($this->rules, fn(DeliveryChargeRule $a, DeliveryChargeRule $b) => $a->getThresholdInCents() <=> $b->getThresholdInCents());
    //    var_dump($this->rules);
        return $this->rules;
    }
}

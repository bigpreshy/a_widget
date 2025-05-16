<?php

namespace AcmeWidgetCo\Services;

use AcmeWidgetCo\Repositories\DeliveryChargeRuleRepository;

class DeliveryChargeService
{
    public function __construct(
        private DeliveryChargeRuleRepository $ruleRepository
    ) {}

    public function calculate(int $totalInCents): int
    {
        $rules = $this->ruleRepository->getAllRulesSortedByThreshold();
        $reversedRules = array_reverse($rules);

        foreach ($reversedRules as $rule) {
            if ($totalInCents >= $rule->getThresholdInCents()) {
                return $rule->getChargeInCents();
            }
        }

        // Fallback, though with a 0-threshold rule, the loop should always find a match.
        // If rules array could be empty or not have a 0 threshold, this might need adjustment.
        // Given current setup (DeliveryChargeRuleRepository providing a 0 threshold rule),
        // this part of the code should ideally not be reached.
        return 0; 
    }
}
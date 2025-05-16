<?php

namespace AcmeWidgetCo\DataTransferObjects;

class DeliveryChargeRule
{
    public function __construct(
        public int $thresholdInCents,
        public int $chargeInCents
    ) {}

    public function getThresholdInCents(): int
    {
        return $this->thresholdInCents;
    }

    public function getChargeInCents(): int
    {
        return $this->chargeInCents;
    }

    /**
     * @return array{threshold_in_cents: int, charge_in_cents: int}
     */
    public function toArray(): array
    {
        return [
            'threshold_in_cents' => $this->getThresholdInCents(),
            'charge_in_cents' => $this->getChargeInCents(),
        ];
    }
}
<?php

namespace AcmeWidgetCo\DataTransferObjects;

use AcmeWidgetCo\Enums\SpecialOffer;

class SpecialOfferDetails
{
    /**
     * @param string[] $targetProductCodes
     */
    public function __construct(
        public string $code,
        public string $name,
        public SpecialOffer $type,
        public array $targetProductCodes = [],
        public int $quantityRequirement = 0,
        public bool $isActive = true
    ) {}

    public function getCode(): string
    {
        return $this->code;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): SpecialOffer
    {
        return $this->type;
    }

    /**
     * @return string[]
     */
    public function getTargetProductCodes(): array
    {
        return $this->targetProductCodes;
    }

    public function getQuantityRequirement(): int
    {
        return $this->quantityRequirement;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    /**
     * @return array{code: string, name: string, type: SpecialOffer, target_product_codes: string[], quantity_requirement: int, is_active: bool}
     */
    public function toArray(): array
    {
        return [
            'code' => $this->getCode(),
            'name' => $this->getName(),
            'type' => $this->getType(),
            'target_product_codes' => $this->getTargetProductCodes(),
            'quantity_requirement' => $this->getQuantityRequirement(),
            'is_active' => $this->isActive(),
        ];
    }
}

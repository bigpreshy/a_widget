<?php

namespace AcmeWidgetCo\DataTransferObjects;

class ProductData
{
    public function __construct(
        private string $code,
        private string $name,
        private int $priceInCents
    ) {}

    public function getCode(): string
    {
        return $this->code;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPriceInCents(): int
    {
        return $this->priceInCents;
    }

    /**
     * @return array{code: string, name: string, price_in_cents: int}
     */
    public function toArray(): array
    {
        return [
            'code' => $this->getCode(),
            'name' => $this->getName(),
            'price_in_cents' => $this->getPriceInCents(),
        ];
    }
}

<?php

namespace AcmeWidgetCo\Foundation;

use AcmeWidgetCo\DataTransferObjects\ProductData;

class ProductCollection
{
    /** @var ProductData[] */
    private array $items = [];

    /**
     * @param ProductData[] $items
     */
    public function __construct(array $items = [])
    {
        $this->items = $items;
    }

    public function add(ProductData $product): void
    {
        $this->items[] = $product;
    }

    /**
     * @return ProductData[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    public function filter(callable $callback): ProductCollection
    {
        $filteredItems = array_filter($this->items, $callback);
        $collection = new self();
        foreach ($filteredItems as $item) {
            $collection->add($item);
        }
        return $collection;
    }

    public function count(): int
    {
        return count($this->items);
    }

    public function isEmpty(): bool
    {
        return empty($this->items);
    }

    public function first(): ?ProductData
    {
        return $this->items[0] ?? null;
    }
}

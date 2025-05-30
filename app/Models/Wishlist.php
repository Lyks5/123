<?php

namespace App\Models;

class Wishlist
{
    protected $items;
    protected $name;

    public function __construct(string $name = 'Избранное', array $items = [])
    {
        $this->name = $name;
        $this->items = $items;
    }

    public function addItem(int $productId, ?int $variantId = null): void
    {
        $this->items[] = [
            'product_id' => $productId,
            'variant_id' => $variantId
        ];
    }

    public function removeItem(int $productId, ?int $variantId = null): void
    {
        $this->items = array_filter($this->items, function ($item) use ($productId, $variantId) {
            return !($item['product_id'] === $productId && $item['variant_id'] === $variantId);
        });
    }

    public function hasItem(int $productId, ?int $variantId = null): bool
    {
        return (bool) array_filter($this->items, function ($item) use ($productId, $variantId) {
            return $item['product_id'] === $productId && $item['variant_id'] === $variantId;
        });
    }

    public function toArray(): array
    {
        return [
            'list_name' => $this->name,
            'items' => array_values($this->items)
        ];
    }
}
<?php

namespace App\Models;

use Illuminate\Support\Collection;

class Cart
{
    protected $userId;
    protected array $rawItems;
    protected ?Collection $processedItems = null;
    public $total_amount = 0;

    public function __construct($userId = null, array $items = [])
    {
        $this->userId = $userId;
        $this->rawItems = is_array($items) ? $items : [];
        $this->processedItems = null;
        $this->total_amount = 0;
    }

    public function addItem(int $productId, ?int $variantId = null, int $quantity = 1): void
    {
        $itemKey = $this->getItemKey($productId, $variantId);
        
        if (isset($this->rawItems[$itemKey])) {
            $this->rawItems[$itemKey]['quantity'] += $quantity;
        } else {
            $this->rawItems[] = [
                'product_id' => $productId,
                'variant_id' => $variantId,
                'quantity' => $quantity
            ];
        }
        $this->processedItems = null; // Сбрасываем кэш обработанных items
    }

    public function removeItem(int $productId, ?int $variantId = null): void
    {
        $itemKey = $this->getItemKey($productId, $variantId);
        unset($this->rawItems[$itemKey]);
        $this->processedItems = null;
    }

    public function updateQuantity(int $productId, ?int $variantId = null, int $quantity): void
    {
        $itemKey = $this->getItemKey($productId, $variantId);
        if (isset($this->rawItems[$itemKey])) {
            $this->rawItems[$itemKey]['quantity'] = $quantity;
            $this->processedItems = null;
        }
    }

    public function hasItem(int $productId, ?int $variantId = null): bool
    {
        $itemKey = $this->getItemKey($productId, $variantId);
        return isset($this->rawItems[$itemKey]);
    }

    public function getItems()
    {
        if ($this->processedItems === null) {
            $this->processedItems = collect();
            foreach (array_values($this->rawItems) as $item) {
                if (!isset($item['product_id'])) {
                    continue;
                }
                
                $product = Product::find($item['product_id']);
                if (!$product || $product->status !== 'published') {
                    continue;
                }

                $variant = isset($item['variant_id']) ? Variant::find($item['variant_id']) : null;
                if ($item['variant_id'] && !$variant) {
                    continue;
                }

                $this->processedItems->push([
                    'product' => $product,
                    'variant' => $variant,
                    'quantity' => $item['quantity'] ?? 1
                ]);
            }

            // Обновляем total_amount
            $this->total_amount = $this->processedItems->sum(function ($item) {
                $price = $item['variant'] ? $item['variant']->price : $item['product']->price;
                return $price * $item['quantity'];
            });
        }

        return $this->processedItems;
    }

    public function clear(): void
    {
        $this->rawItems = [];
        $this->processedItems = null;
        $this->total_amount = 0;
    }

    public function isEmpty(): bool
    {
        return empty($this->rawItems);
    }

    public function getSubtotal()
    {
        return $this->getItems()->sum(function ($item) {
            $price = $item['variant'] ? $item['variant']->price : $item['product']->price;
            return $price * $item['quantity'];
        });
    }

    public function __get($name)
    {
        if ($name === 'items') {
            return $this->getItems();
        }
        
        return null;
    }

    public function toArray(): array
    {
        // Убедимся, что items - это массив индексированный числами
        $items = array_values($this->rawItems);
        
        return [
            'user_id' => $this->userId,
            'items' => $items
        ];
    }

    private function getItemKey(int $productId, ?int $variantId = null): string
    {
        return $variantId ? "{$productId}_{$variantId}" : (string)$productId;
    }

    public function count()
    {
        if (empty($this->rawItems)) {
            return 0;
        }
        return collect($this->rawItems)->sum('quantity');
    }
}
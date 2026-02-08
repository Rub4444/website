<?php

namespace App\Classes;

use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Log;

/**
 * Redis-слой для корзины. Ключ: cart:{user_id} или cart:guest:{session_id}.
 * Значение: hash sku_id => count (float). При недоступности Redis все методы тихо отказывают.
 */
class CartStore
{
    protected string $keyPrefix;
    protected bool $enabled;
    protected int $guestTtl;

    public function __construct()
    {
        $this->keyPrefix = config('cart.redis.key_prefix', 'cart:');
        $this->enabled = config('cart.redis.enabled', true);
        $this->guestTtl = (int) config('cart.redis.ttl_guest_seconds', 604800);
    }

    public function key(?int $userId, ?string $sessionId = null): string
    {
        if ($userId) {
            return $this->keyPrefix . $userId;
        }
        return $this->keyPrefix . 'guest:' . ($sessionId ?: session()->getId());
    }

    public function isGuestKey(string $key): bool
    {
        return str_starts_with($key, $this->keyPrefix . 'guest:');
    }

    /**
     * @return array<int, float> sku_id => count
     */
    public function getItems(?int $userId, ?string $sessionId = null): array
    {
        if (!$this->enabled) {
            return [];
        }
        try {
            $key = $this->key($userId, $sessionId);
            $raw = Redis::hGetAll($key);
            if (!$raw) {
                return [];
            }
            $out = [];
            foreach ($raw as $skuId => $count) {
                $out[(int) $skuId] = (float) $count;
            }
            return $out;
        } catch (\Throwable $e) {
            Log::warning('CartStore::getItems failed', ['message' => $e->getMessage()]);
            return [];
        }
    }

    /**
     * @param array<int, float> $items sku_id => count
     */
    public function setItems(?int $userId, array $items, ?string $sessionId = null): bool
    {
        if (!$this->enabled) {
            return false;
        }
        try {
            $key = $this->key($userId, $sessionId);
            Redis::del($key);
            if ($items !== []) {
                $payload = [];
                foreach ($items as $skuId => $count) {
                    $payload[(string) $skuId] = (string) $count;
                }
                Redis::hMSet($key, $payload);
                if ($this->isGuestKey($key)) {
                    Redis::expire($key, $this->guestTtl);
                }
            }
            return true;
        } catch (\Throwable $e) {
            Log::warning('CartStore::setItems failed', ['message' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Атомарно изменить количество по sku_id. Если count <= 0, запись удаляется.
     */
    public function addQuantity(?int $userId, int $skuId, float $delta, ?string $sessionId = null): bool
    {
        if (!$this->enabled) {
            return false;
        }
        try {
            $key = $this->key($userId, $sessionId);
            $newCount = (float) Redis::hIncrByFloat($key, (string) $skuId, $delta);
            if ($newCount <= 0) {
                Redis::hDel($key, (string) $skuId);
            }
            if ($this->isGuestKey($key)) {
                Redis::expire($key, $this->guestTtl);
            }
            return true;
        } catch (\Throwable $e) {
            Log::warning('CartStore::addQuantity failed', ['message' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Установить количество по sku_id. 0 или меньше — удалить.
     */
    public function setQuantity(?int $userId, int $skuId, float $count, ?string $sessionId = null): bool
    {
        if (!$this->enabled) {
            return false;
        }
        try {
            $key = $this->key($userId, $sessionId);
            if ($count <= 0) {
                Redis::hDel($key, (string) $skuId);
            } else {
                Redis::hSet($key, (string) $skuId, (string) $count);
            }
            if ($this->isGuestKey($key)) {
                Redis::expire($key, $this->guestTtl);
            }
            return true;
        } catch (\Throwable $e) {
            Log::warning('CartStore::setQuantity failed', ['message' => $e->getMessage()]);
            return false;
        }
    }

    public function forget(?int $userId, ?string $sessionId = null): bool
    {
        if (!$this->enabled) {
            return false;
        }
        try {
            Redis::del($this->key($userId, $sessionId));
            return true;
        } catch (\Throwable $e) {
            Log::warning('CartStore::forget failed', ['message' => $e->getMessage()]);
            return false;
        }
    }
}

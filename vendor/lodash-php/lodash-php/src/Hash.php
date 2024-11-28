<?php
namespace _;

use _\internal\Traits\CacheDataTrait;

final class Hash implements CacheInterface
{
    use CacheDataTrait;

    public function __construct()
    {
        $this->clear();
    }

    public function set($key, $value): CacheInterface
    {
        $this->size += $this->has($key) ? 0 : 1;
        $this->__data__[$key] = $value;
        return $this;
    }

    public function get($key)
    {
        return $this->__data__[$key] ?? null;
    }

    public function has($key): bool
    {
        return \array_key_exists($key, $this->__data__);
    }

    public function clear()
    {
        $this->__data__ = [];
        $this->size = 0;
    }

    public function delete($key)
    {
        $result = $this->has($key);
        if ($result) {
            unset($this->__data__[$key]);
            $this->size--;
        }
        return $result;
    }

    public function getSize()
    {
        return $this->size;
    }
}
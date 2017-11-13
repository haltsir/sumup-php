<?php

namespace Sumup\Api\Sumup\Cache\File;

use Psr\Cache\CacheItemInterface;
use Psr\Cache\CacheItemPoolInterface;
use Sumup\Api\Sumup\Cache\Exception\InvalidArgumentException;

class FileCacheItemPool implements CacheItemPoolInterface
{
    const PATH_SUFFIX = DIRECTORY_SEPARATOR . 'sumup-php';

    /**
     * @var string
     */
    protected $storagePath;

    /**
     * @var array
     */
    protected $queue;

    /**
     * FileCacheItemPool constructor.
     * @param string|null $storagePath
     */
    public function __construct(string $storagePath = null)
    {
        $this->storagePath = rtrim(
                $storagePath ?: sys_get_temp_dir() . self::PATH_SUFFIX,
                '/\\'
            )
            . DIRECTORY_SEPARATOR;

        if (!is_dir($this->storagePath)) {
            mkdir($this->storagePath, 0700, true);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getItem($key)
    {
        if (false === $this->isValidKey($key)) {
            throw new InvalidArgumentException();
        }

        if (!file_exists($this->storagePath . $key)) {
            return new FileCacheItem($key);
        }

        $contents = file_get_contents($this->storagePath . $key);
        /** @var FileCacheItem $item */
        $item = unserialize($contents);

        if (!($item instanceof FileCacheItem)) {
            unlink($this->storagePath . $key);

            return new FileCacheItem($key);
        }

        if ($item->hasExpired()) {
            return new FileCacheItem($key);
        }

        return $item;
    }

    /**
     * {@inheritdoc}
     */
    public function getItems(array $keys = array())
    {
        $items = [];
        foreach ($keys as $key) {
            $items[$key] = $this->getItem($key);
        }

        return $items;
    }

    /**
     * {@inheritdoc}
     */
    public function hasItem($key)
    {
        $item = $this->getItem($key);

        return $item->isHit();
    }

    /**
     * {@inheritdoc}
     */
    public function clear()
    {
        $success = true;
        $items = new \DirectoryIterator($this->storagePath);
        foreach ($items as $item) {
            if ($item->isFile() && !$item->isDot()) {
                $success = $success && unlink($item->getPathname());
            }
        }

        return $success;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteItem($key)
    {
        if (!$this->isValidKey($key)) {
            throw new \InvalidArgumentException();
        }

        return file_exists($this->storagePath . $key) && unlink($this->storagePath . $key);
    }

    /**
     * {@inheritdoc}
     */
    public function deleteItems(array $keys)
    {
        if (false === array_walk($keys, [$this, 'isValidKey'])) {
            throw new \InvalidArgumentException();
        }

        $success = true;
        foreach ($keys as $key) {
            $success = $success && $this->deleteItem($key);
        }

        return $success;
    }

    /**
     * {@inheritdoc}
     */
    public function save(CacheItemInterface $item)
    {
        if (!($item instanceof FileCacheItem)) {
            return false;
        }

        $contents = serialize($item);

        return !!file_put_contents($this->storagePath . $item->getKey(), $contents);
    }

    /**
     * {@inheritdoc}
     */
    public function saveDeferred(CacheItemInterface $item)
    {
        return !!($this->queue[$item->getKey()] = $item);
    }

    /**
     * {@inheritdoc}
     */
    public function commit()
    {
        $success = true;
        foreach ($this->queue as $item) {
            $success = $success && $this->save($item);

            if (false === $success) {
                break;
            }
        }

        return $success;
    }

    /**
     * Validate key in accordance to PSR-6.
     *
     * @param $key
     * @return bool
     */
    private function isValidKey($key)
    {
        return (is_string($key) && preg_match('#^[A-Za-z0-9_.-]+$#', $key) > 0);
    }
}

<?php

namespace Sumup\Api\Cache\File;

use Psr\Cache\CacheItemInterface;
use Sumup\Api\Cache\Exception\InvalidArgumentException;

class FileCacheItem implements CacheItemInterface
{
    /**
     * @var bool
     */
    protected $isHit = false;

    /**
     * @var \DateTimeInterface
     */
    protected $expiration = null;

    /**
     * @var string
     */
    protected $key;

    /**
     * @var mixed
     */
    protected $value;

    public function __construct(string $key)
    {
        $this->key = $key;
    }

    /**
     * {@inheritdoc}
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * {@inheritdoc}
     */
    public function get()
    {
        return $this->isHit() ? null : $this->value;
    }

    /**
     * {@inheritdoc}
     */
    public function isHit()
    {
        if (false === $this->isHit) {
            return false;
        }

        return (null === $this->expiration ?: new \DateTime() < $this->expiration);
    }

    /**
     * {@inheritdoc}
     */
    public function set($value)
    {
        $this->value = $value;
        $this->isHit = true;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function expiresAt($expiration)
    {
        if (false === $this->isValidExpiration($expiration)) {
            return false;
        }

        $this->expiration = is_int($expiration)
            ? new \DateTime('@' . $expiration)
            : $expiration;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function expiresAfter($time)
    {
        if (false === $this->isValidInterval($time)) {
            return false;
        }

        if (null === $this->expiration) {
            $this->expiration = (new \DateTime())->add($this->getIntervalFromTime($time));

            return $this;
        }

        if ($this->expiration instanceof \DateTime) {
            $this->expiration->add($this->getIntervalFromTime($time));

            return $this;
        }

        return false;
    }

    /**
     * Check whether cache item has expired.
     *
     * @return bool
     */
    public function hasExpired()
    {
        return null !== $this->expiration && $this->expiration < new \DateTime();
    }

    /**
     * Check for expiration validity according to PSR-6
     *
     * @param $expiration
     * @return bool
     */
    private function isValidExpiration($expiration)
    {
        if ($expiration instanceof \DateTimeInterface && $expiration <= new \DateTime()) {
            return false;
        }

        return is_int($expiration) || is_null($expiration);
    }

    /**
     * Check for date interval validity according to PSR-6
     *
     * @param $time
     * @return bool
     */
    private function isValidInterval($time)
    {
        return ($time instanceof \DateInterval || is_int($time) || is_null($time));
    }

    /**
     * Convert $time to a DateInterval object if needed.
     *
     * @param $time
     * @return \DateInterval
     * @throws InvalidArgumentException
     */
    private function getIntervalFromTime($time)
    {
        if ($time instanceof \DateInterval) {
            return $time;
        }

        if (is_int($time)) {
            return new \DateInterval('PT' . $time . 'S');
        }

        throw new InvalidArgumentException();
    }
}

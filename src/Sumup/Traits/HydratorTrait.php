<?php

namespace Sumup\Api\Traits;

use Sumup\Api\Request\Exception\InvalidArgumentException;

trait HydratorTrait
{
    public function hydrate(array $data)
    {
        if (!is_array($data)) {
            throw new InvalidArgumentException();
        }

        if (defined('self::MAP')) {
            return $this->hydrateMap($data);
        }

        foreach ($this as $property) {
            if (!array_key_exists($property, $data)) {
                continue;
            }

            $this->$property = $data[$property];
        }

        return $this;
    }

    private function hydrateMap(array $data)
    {
        if (false === defined('self::MAP')) {
            return $this;
        }

        foreach (array_keys((array)$this) as $property) {
            if (!isset(self::MAP[$property])) {
                if (isset($data[$property])) {
                    $this->$property = $data[$property];
                }
                continue;
            }

            $map = self::MAP[$property];
            $this->$property = $this->resolveMap($property, $map, $data);
        }

        return $this;
    }

    private function resolveMap($property, $map, $data)
    {
        $path = $map['path'] ?? $property;
        $type = $map['type'] ?? 'string';

        if (false === strpos($path, '.')) {
            return $this->resolveValue($type, $data[$path] ?? null);
        }

        $value = $data;
        $keys = explode('.', $path);
        foreach ($keys as $key) {
            if (!isset($value[$key])) {
                return null;
            }
            $value = $value[$key];
        }

        return $this->resolveValue($type, $value);
    }

    /**
     * @param string $type
     * @param mixed $data
     * @return mixed
     */
    private function resolveValue(string $type, $data)
    {
        return (class_exists($type) && is_array($data) ? (new $type)->hydrate($data) : $data);
    }
}

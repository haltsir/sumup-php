<?php

namespace Sumup\Api\Traits;

trait HydratorTrait
{
    /**
     * Hydrate entity based on data from the API.
     *
     * @param array $data
     * @return $this
     */
    public function hydrate(array $data)
    {
        if (defined('self::MAP_JSON_TO_ENTITY')) {
            return $this->hydrateMap($data);
        }

        foreach (array_keys(get_object_vars($this)) as $property) {
            $key = camelCaseToSnakeCase($property);
            if (array_key_exists($key, $data)) {
                $this->$property = $data[$key];
            }

            if (!array_key_exists($property, $data)) {
                continue;
            }

            $this->$property = $data[$property];
        }

        return $this;
    }

    /**
     * Hydrate mapped entity.
     *
     * @param array $data
     * @return $this
     */
    private function hydrateMap(array $data)
    {
        if (false === defined('self::MAP_JSON_TO_ENTITY')) {
            return $this;
        }

        foreach (array_keys(get_object_vars($this)) as $property) {
            if (!isset(self::MAP_JSON_TO_ENTITY[$property])) {
                if (isset($data[$property])) {
                    $this->$property = $data[$property];
                }
                continue;
            }

            $map = self::MAP_JSON_TO_ENTITY[$property];
            $this->$property = $this->resolveHydrationMap($property, $map, $data);
        }

        return $this;
    }

    /**
     * Resolve map keys and values to their entity counterparts.
     *
     * @param $property
     * @param $map
     * @param $data
     * @return array|mixed|null
     */
    private function resolveHydrationMap($property, $map, $data)
    {
        $path = $map['path'] ?? $property;
        $type = $map['type'] ?? 'string';
        $subtype = $map['subtype'] ?? null;

        if (false === strpos($path, '.')) {
            return $this->resolveHydrationValueByType($type, $subtype, $data[$path] ?? null);
        }

        $value = $data;
        $keys = explode('.', $path);
        foreach ($keys as $key) {
            if (!isset($value[$key])) {
                return null;
            }
            $value = $value[$key];
        }

        return $this->resolveHydrationValue($type, $value);
    }

    /**
     * Resolve value by key type.
     *
     * @param $type
     * @param $subtype
     * @param $data
     * @return array|mixed
     */
    private function resolveHydrationValueByType($type, $subtype, $data)
    {
        switch ($type) {
            case 'array':
                return $this->resolveHydrationValueArray($subtype, $data);
            case 'string':
            default:
                return $this->resolveHydrationValue($type, $data);
        }
    }

    /**
     * Resolve value of type class and string.
     *
     * @param string $type
     * @param mixed $data
     * @return mixed
     */
    private function resolveHydrationValue(string $type, $data)
    {
        return (class_exists($type) && is_array($data) ? (new $type)->hydrate($data) : $data);
    }

    /**
     * Resolve value of type array.
     *
     * @param $type
     * @param $data
     * @return array
     */
    private function resolveHydrationValueArray($type, $data)
    {
        $results = [];
        if(empty($data)) {
            return $results;
        }

        foreach ($data as $subdata) {
            $results[] = $this->resolveHydrationValue($type, $subdata);
        }

        return $results;
    }
}

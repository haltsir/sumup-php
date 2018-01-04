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
            return $this->hydrateMap($this, $data, self::MAP_JSON_TO_ENTITY);
        }

        return $this->hydrateObject($this, $data);
    }

    private function hydrateObject($object, $data)
    {
        foreach (array_keys(get_object_vars($object)) as $property) {
            $key = camelCaseToSnakeCase($property);
            if (array_key_exists($key, $data)) {
                $object->$property = $data[$key];
            }

            if (!array_key_exists($property, $data)) {
                continue;
            }

            $object->$property = $data[$property];
        }

        return $object;
    }

    /**
     * Hydrate mapped entity.
     *
     * @param $object
     * @param array $data
     * @param array $map
     * @return $this
     */
    private function hydrateMap($object, array $data, array $map)
    {
        foreach (array_keys(get_object_vars($object)) as $property) {
            if (!isset($map[$property])) {
                $key = camelCaseToSnakeCase($property);
                if (isset($data[$key])) {
                    $object->$property = $data[$key];
                }
                continue;
            }

            $localMap = self::MAP_JSON_TO_ENTITY[$property];
            $object->$property = $this->resolveHydrationMap($property, $localMap, $data);
        }

        return $object;
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
        if (empty($data)) {
            return $results;
        }

        foreach ($data as $subdata) {
            $results[] = $this->resolveHydrationValue($type, $subdata);
        }

        return $results;
    }
}

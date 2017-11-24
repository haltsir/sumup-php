<?php

namespace Sumup\Api\Traits;

trait SerializerTrait
{
    /**
     * Serialize entity to JSON.
     *
     * @return mixed
     */
    public function serialize()
    {
        $map = defined('self::MAP_ENTITY_TO_JSON') ? self::MAP_ENTITY_TO_JSON : [];

        return json_encode($this->rekeyBeforeSerialize(get_object_vars($this), $map));
    }

    /**
     * Change data keys to API format. Map values.
     *
     * @param array $data
     * @param array $map
     * @return array
     */
    public function rekeyBeforeSerialize(array $data, array $map = [])
    {
        $target = [];

        if (sizeof($map) > 0) {
            $target = $this->mapToSerialize($data);
        }

        foreach ($data as $key => $value) {
            if (is_null($value)) {
                continue;
            }

            $newKey = camelCaseToUnderscore($key);
            if (is_object($value)) {
                $value = get_object_vars($value);
            }

            $insideMap = $map[$key] ?? [];
            $target[$newKey] = is_array($value) ? $this->rekeyBeforeSerialize($value, $insideMap) : $value;
        }

        return $target;
    }

    /**
     * Change data keys.
     *
     * @param array $data
     * @return array
     */
    private function mapToSerialize(array $data)
    {
        $target = [];
        foreach (self::MAP_ENTITY_TO_JSON as $key => $map) {
            $path = $map['path'] ?? $key;

            if (false === strpos($key, '.')) {
                $target[$path] = $data[$key];
                continue;
            }

            $target[$path] = $this->resolveSerializationValue($key, $data);
        }

        return $target;
    }

    /**
     * Resolve value by map key.
     *
     * @param string $key
     * @param array $data
     * @return mixed
     */
    private function resolveSerializationValue(string $key, array $data)
    {
        if (isset($data[$key])) {
            return $data[$key];
        }

        $keyParts = explode('.', $key);
        $value = $data;
        foreach ($keyParts as $part) {
            if (is_object($value) && isset($value->$part)) {
                $value = $value->$part;
                continue;
            }

            if (is_array($value) && isset($value[$part])) {
                $value = $value[$part];
                continue;
            }

            return null;
        }

        return $value;
    }
}

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
        if (defined('self::MAP_ENTITY_TO_JSON')) {
            return json_encode($this->serializeMap($this, self::MAP_ENTITY_TO_JSON));
        }

        $target = $this->serializeValue($this);

        return json_encode($target);
    }

    /**
     * Serialize value. All value serialization should go through this method.
     *
     * @param $value
     * @param string|null $type
     * @return array|null
     */
    private function serializeValue($value, string $type = null)
    {
        if (class_exists($type) && is_object($value)) {
            return $this->serializeNewObject($type, $value);
        }

        switch (gettype($value)) {
            case 'object':
                return $this->serializeObject($value);
            case 'array':
                return (0 === sizeof($value) ? null : $value);
            default:
                return $value;
        }
    }

    /**
     * Translate object to array. Skip null values.
     *
     * @param $object
     * @return array|null
     */
    private function serializeObject($object)
    {
        $target = [];
        foreach (array_keys(get_object_vars($object)) as $entityProperty) {
            $key = camelCaseToSnakeCase($entityProperty);
            $value = $this->serializeValue($object->$entityProperty);
            if (null === $value) {
                continue;
            }
            $target[$key] = $value;
        }

        return (sizeof($target) > 0 ? $target : null);
    }

    /**
     * Create object of type $type and populate its values. Return its serialized version.
     *
     * @param $type
     * @param $value
     * @return array|null
     * @todo Should the defined() check be moved to serializeValue()?
     */
    private function serializeNewObject($type, $value)
    {
        $object = new $type;
        foreach (array_keys(get_object_vars($value)) as $objectProperty) {
            if (property_exists($object, $objectProperty) && null !== $value->$objectProperty) {
                $object->$objectProperty = $value->$objectProperty;
            }
        }

        return defined($type . '::MAP_ENTITY_TO_JSON')
            ? $this->serializeMap($object, $type . '::MAP_ENTITY_TO_JSON')
            : $this->serializeValue($object);
    }

    /**
     * Serialize using a map.
     *
     * @param $object
     * @param array $map
     * @return array
     */
    private function serializeMap($object, array $map = [])
    {
        $target = [];

        foreach (array_keys(get_object_vars($object)) as $entityProperty) {
            if (!array_key_exists($entityProperty, $map)) {
                $key = camelCaseToSnakeCase($entityProperty);
                $value = $this->serializeValue($object->$entityProperty);
                if (null !== $value) {
                    $target[$key] = $value;
                } else {
                    unset($target[$key]);
                }
                continue;
            }

            $propertyMap = self::MAP_ENTITY_TO_JSON[$entityProperty];
            $itemKey = $propertyMap['path'] ?? $entityProperty;
            $itemType = $propertyMap['type'] ?? 'string';

            // If map's path is an array, assign each of the object's properties according to the map.
            if (is_array($itemKey)) {
                foreach ($itemKey as $foreignProperty => $localProperty) {
                    $target[$localProperty] = $object->$entityProperty->$foreignProperty;
                }
                continue;
            }

            if (false === strpos($itemKey, '.')) {
                $value = $this->serializeValue($object->$entityProperty, $itemType);
                if (null !== $value) {
                    $target[$itemKey] = $value;
                }
                continue;
            }

            $target[$itemKey] = [];
            $variableBuilder =& $target[$itemKey];
            foreach (explode('.', $itemKey) as $idx => $key) {
                $variableBuilder[$key] = [];
                $variableBuilder =& $variableBuilder[$key];
            }

            $variableBuilderValue = $this->serializeValue($object->$entityProperty, $itemType);
            if (null !== $variableBuilderValue) {
                $variableBuilder = $variableBuilderValue;
            } else {
                unset($target[$itemKey]);
            }
        }

        return $target;
    }
}

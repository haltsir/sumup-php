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
        if (false === defined('self::MAP_ENTITY_TO_JSON')) {
            return $this;
        }

        return json_encode($this->serializeMap($this, self::MAP_ENTITY_TO_JSON));
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
                $target[$key] = $object->$entityProperty;
                continue;
            }

            $propertyMap = self::MAP_ENTITY_TO_JSON[$entityProperty];
            $itemKey = $propertyMap['path'] ?? $entityProperty;
            $itemType = $propertyMap['type'] ?? 'string';

            if (false === strpos($itemKey, '.')) {
                $target[$itemKey] =
                    $this->serializeValue($itemType, $object->$entityProperty, $object, $entityProperty);
                continue;
            }

            $target[$itemKey] = [];
            $variableBuilder =& $target[$itemKey];
            foreach (explode('.', $itemKey) as $idx => $key) {
                $variableBuilder[$key] = [];
                $variableBuilder =& $variableBuilder[$key];
            }

            $variableBuilder = $this->serializeValue($itemType, $object->$entityProperty, $object, $entityProperty);
        }

        return $target;
    }

    /**
     * Serialize object property value.
     *
     * @param $type
     * @param $value
     * @param $parentObject
     * @param $parentProperty
     * @return mixed
     */
    private function serializeValue($type, $value, $parentObject, $parentProperty)
    {
        if (class_exists($type) && is_object($parentObject->$parentProperty)) {
            return $this->serializeValueByObject($type, $parentObject, $parentProperty);
        }

        return $value;
    }

    /**
     * Serialize value of type object.
     *
     * @param $type
     * @param $parentObject
     * @param $parentProperty
     * @return array
     */
    private function serializeValueByObject($type, $parentObject, $parentProperty)
    {
        $map = (defined($type . '::MAP_ENTITY_TO_JSON') ? $type . '::MAP_ENTITY_TO_JSON' : []);
        $object = new $type;
        foreach (array_keys(get_object_vars($parentObject->$parentProperty)) as $objectProperty) {
            if (property_exists($object, $objectProperty)) {
                $object->$objectProperty = $parentObject->$parentProperty->$objectProperty;
            }
        }

        return $this->serializeMap($object, $map);
    }
}

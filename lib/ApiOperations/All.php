<?php

namespace Mastercard\ApiOperations;

/**
 * Trait for listable resources. Adds a `all()` static method to the class.
 *
 * This trait should only be applied to classes that derive from MastercardObject.
 */
trait All
{
    /**
     * @param array|null $params
     * @param array|string|null $opts
     *
     * @return \Mastercard\Collection of ApiResources
     */
    public static function all($params = null, $opts = null)
    {
        self::_validateParams($params);
        $url = static::classUrl() . '/list';

        list($response, $opts) = static::_staticRequest('post', $url, $params, $opts);
        $obj = \Mastercard\Util\Util::convertToMastercardObject($response->json, $opts);
        if (!($obj instanceof \Mastercard\Collection)) {
            throw new \Mastercard\Exception\UnexpectedValueException(
                'Expected type ' . \Mastercard\Collection::class . ', got "' . get_class($obj) . '" instead.'
            );
        }
        $obj->setLastResponse($response);
        $obj->setFilters($params);
        return $obj;
    }
}

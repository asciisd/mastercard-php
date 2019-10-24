<?php

namespace Mastercard\ApiOperations;

use Mastercard\MastercardObject;
use Mastercard\Util\Util;

/**
 * Trait for creatable resources. Adds a `create()` static method to the class.
 *
 * This trait should only be applied to classes that derive from MastercardObject.
 */
trait Create
{
    /**
     * @param array|null $params
     * @param array|string|null $options
     *
     * @return array|MastercardObject|\Mastercard\Customer The created resource.
     */
    public static function create($params = null, $options = null)
    {
        self::_validateParams($params);
        $url = static::classUrl();

        list($response, $opts) = static::_staticRequest('post', $url, $params, $options);
        $obj = Util::convertToMastercardObject($response->json, $opts);
        $obj->setLastResponse($response);
        return $obj;
    }
}

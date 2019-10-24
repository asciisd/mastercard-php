<?php

namespace Mastercard\ApiOperations;

/**
 * Trait for retrievable resources. Adds a `retrieve()` static method to the
 * class.
 *
 * This trait should only be applied to classes that derive from MastercardObject.
 */
trait Retrieve
{
    /**
     * @param array|string $id The ID of the API resource to retrieve,
     *     or an options array containing an `id` key.
     * @param array|string|null $opts
     *
     * @param null $second_id
     * @return static
     */
    public static function retrieve($id, $opts = null, $second_id = null)
    {
        $opts = \Mastercard\Util\RequestOptions::parse($opts);
        $instance = new static($id, $opts, $second_id);
        $instance->refresh();
        return $instance;
    }
}

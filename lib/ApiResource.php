<?php

namespace Mastercard;

/**
 * Class ApiResource
 *
 * @package Mastercard
 */
abstract class ApiResource extends MastercardObject
{
    use ApiOperations\Request;

    /**
     * @return Util\Set|null A list of fields that can be their own type of
     * API resource (say a nested card under an account for example), and if
     * that resource is set, it should be transmitted to the API on a create or
     * update. Doing so is not the default behavior because API resources
     * should normally be persisted on their own RESTful endpoints.
     */
    public static function getSavedNestedResources()
    {
        static $savedNestedResources = null;
        if ($savedNestedResources === null) {
            $savedNestedResources = new Util\Set();
        }
        return $savedNestedResources;
    }

    /**
     * @var boolean A flag that can be set a behavior that will cause this
     * resource to be encoded and sent up along with an update of its parent
     * resource. This is usually not desirable because resources are updated
     * individually on their own endpoints, but there are certain cases,
     * replacing a customer's source for example, where this is allowed.
     */
    public $saveWithParent = false;

    public function __set($k, $v)
    {
        parent::__set($k, $v);
        $v = $this->$k;
        if ((static::getSavedNestedResources()->includes($k)) &&
            ($v instanceof ApiResource)) {
            $v->saveWithParent = true;
        }
        return $v;
    }

    /**
     * @return ApiResource The refreshed resource.
     *
     * @throws Exception\ApiErrorException
     */
    public function refresh()
    {
        $requestor = new ApiRequestor($this->_opts->apiKey, static::baseUrl());
        $url = $this->instanceUrl();

        list($response, $this->_opts->apiKey) = $requestor->request(
            'get',
            $url,
            $this->_retrieveOptions,
            $this->_opts->headers
        );
        $this->setLastResponse($response);
        $this->refreshFrom($response->json, $this->_opts);
        return $this;
    }

    /**
     * @return string The base URL for the given class.
     */
    public static function baseUrl()
    {
        return Mastercard::getGatewayUrl();
    }

    /**
     * @return string The endpoint URL for the given class.
     */
    public static function classUrl()
    {
        // Replace dots with slashes for namespaced resources, e.g. if the object's name is
        // "foo.bar", then its URL will be "/v1/foo/bars".
        $base = str_replace('.', '/', static::OBJECT_NAME);
        return "/${base}";
    }

    /**
     * @return string The endpoint URL for the given class.
     */
    public static function firstClassUrl()
    {
        // Replace dots with slashes for namespaced resources, e.g. if the object's name is
        // "foo.bar", then its URL will be "/v1/foo/bars".
        $base = str_replace('.', '/', static::FIRST_OBJECT_NAME);
        return "/${base}";
    }

    /**
     * @return string The endpoint URL for the given class.
     */
    public static function secondClassUrl()
    {
        // Replace dots with slashes for namespaced resources, e.g. if the object's name is
        // "foo.bar", then its URL will be "/v1/foo/bars".
        $base = str_replace('.', '/', static::SECOND_OBJECT_NAME);
        return "${base}";
    }

    /**
     * @param $id
     * @param null $second_id
     * @return string The instance endpoint URL for the given class.
     */
    public static function resourceUrl($id, $second_id = null)
    {
        if ($id === null) {
            $class = get_called_class();
            $message = "Could not determine which URL to request: "
                . "$class instance has invalid ID: $id";
            throw new Exception\UnexpectedValueException($message);
        }

        if ($second_id !== null) {
            $id = Util\Util::utf8($id);
            $second_id = Util\Util::utf8($second_id);
            $first_base = static::firstClassUrl();
            $first_extn = urlencode($id);
            $second_base = static::secondClassUrl();
            $second_extn = urlencode($second_id);
            return "$first_base/$first_extn/$second_base/$second_extn";
        }

        $id = Util\Util::utf8($id);
        $base = static::classUrl();
        $extn = urlencode($id);

        return "$base/$extn";
    }

    /**
     * @param $id
     * @return string The instance endpoint URL for the given class.
     */
    public static function nestedResourceUrl($id)
    {
        if ($id === null) {
            $class = get_called_class();
            $message = "Could not determine which URL to request: "
                . "$class instance has invalid ID: $id";
            throw new Exception\UnexpectedValueException($message);
        }
        $id = Util\Util::utf8($id);
        $extn = urlencode($id);
        return "/$extn";
        // return customer_id
    }

    /**
     * @return string The full API URL for this API resource.
     */
    public function instanceUrl()
    {
        if ($this['id'] === null) {
            return static::resourceUrl($this[static::OBJECT_NAME]['id']);
        }

        return static::resourceUrl($this['id'], $this['second_id']);
    }
}

<?php


namespace Mastercard;


use Mastercard\Enums\MastercardApiOperations as ApiOp;
use Mastercard\Util\Factory;

class Transaction extends ApiResource
{
    const OBJECT_NAME = "transaction";

    use ApiOperations\update;

    //TODO: add pay statuses to this class

    public static function pay($trans_id, $order_id, Factory $factory, $opts = null)
    {
        $params = $factory->apiOperation(ApiOp::PAY)->get();

        self::_validateParams($params);
        $url = static::payUrl($trans_id, $order_id);
        list($response, $opts) = static::_staticRequest('put', $url, $params, $opts);
        $obj = \Mastercard\Util\Util::convertToMastercardObject($response->json, $opts);
        $obj->setLastResponse($response);
        return $obj;
    }

    public static function payUrl($trans_id, $order_id)
    {
        if ($trans_id === null || $order_id === null) {
            $class = get_called_class();
            $message = "Could not determine which URL to request: "
                . "$class instance has invalid ID: $trans_id"
                . "\nand has invalid ID: $order_id";
            throw new Exception\UnexpectedValueException($message);
        }
        $trans_id = Util\Util::utf8($trans_id);
        $order_id = Util\Util::utf8($order_id);
        $trans = static::transUrl();
        $order = static::orderUrl();
        $order_extn = urlencode($order_id);
        $trans_extn = urlencode($trans_id);
        return "$order/$order_extn/$trans/$trans_extn";
    }

    /**
     * @return string The endpoint URL for the given class.
     */
    public static function transUrl()
    {
        // Replace dots with slashes for namespaced resources, e.g. if the object's name is
        // "foo.bar", then its URL will be "/v1/foo/bars".
        $base = str_replace('.', '/', static::OBJECT_NAME);
        return "${base}";
    }

    /**
     * @return string The endpoint URL for the given class.
     */
    public static function orderUrl()
    {
        // Replace dots with slashes for namespaced resources, e.g. if the object's name is
        // "foo.bar", then its URL will be "/v1/foo/bars".
        $base = str_replace('.', '/', 'order');
        return "/${base}";
    }
}

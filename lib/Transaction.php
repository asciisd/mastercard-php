<?php


namespace Mastercard;


use Mastercard\Enums\MastercardApiOperations as ApiOp;
use Mastercard\Util\Factory;

class Transaction extends ApiResource
{
    const OBJECT_NAME = "transaction";
    const FIRST_OBJECT_NAME = "order";
    const SECOND_OBJECT_NAME = "transaction";

    // pay operation return status
    const STATUS_FAILURE = 'FAILURE';
    const STATUS_PENDING = 'PENDING';
    const STATUS_SUCCESS = 'SUCCESS';
    const STATUS_UNKNOWN = 'UNKNOWN';

    use ApiOperations\Update;
    use ApiOperations\Retrieve;


    public static function pay($trans_id, $order_id, Factory $factory, $opts = null)
    {
        $params = $factory->apiOperation(ApiOp::PAY)->get();
        return static::update($order_id, $params, $opts, $trans_id);
    }

    public static function void($trans_id, $order_id, Factory $factory, $opts = null)
    {
        $params = $factory->apiOperation(ApiOp::VOID)->get();
        return static::update($order_id, $params, $opts, $trans_id);
    }

    public static function refund($trans_id, $order_id, Factory $factory, $opts = null)
    {
        $params = $factory->apiOperation(ApiOp::REFUND)->get();
        return static::update($order_id, $params, $opts, $trans_id);
    }

    public static function verify($trans_id, $order_id, Factory $factory, $opts = null)
    {
        $params = $factory->apiOperation(ApiOp::VERIFY)->get();
        return static::update($order_id, $params, $opts, $trans_id);
    }
}

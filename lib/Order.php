<?php


namespace Mastercard;

/**
 * Class Order
 *
 * @property string id
 * @property string amount
 * @property string creationTime
 * @property string currency
 * @property string merchant
 * @property string result
 * @property string totalAuthorizedAmount
 * @property string totalCapturedAmount
 * @property string totalRefundedAmount
 *
 * @package Mastercard
 */
class Order extends ApiResource
{
    const OBJECT_NAME = 'order';

    use ApiOperations\Retrieve;
}

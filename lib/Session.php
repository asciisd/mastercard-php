<?php


namespace Mastercard;

use Mastercard\ApiOperations\Update;
use Mastercard\Enums\MastercardApiOperations as ApiOP;
use Mastercard\Enums\SessionInteractionOperations as SessionOP;
use Mastercard\Util\Factory;
use Mastercard\Util\RandomGenerator;

/**
 * Class Session
 *
 * @property string $correlationId
 * @property string $merchant
 * @property string $result
 * @property SessionObject $session
 * @property string $successIndicator
 * @property Customer $customer
 *
 * @package Mastercard
 */
class Session extends ApiResource
{
    const OBJECT_NAME = "session";

    use ApiOperations\Create;
    use ApiOperations\Retrieve;
    use ApiOperations\Update;

    public static function createCheckout($currency = 'USD', SessionOP $operation = null)
    {
        $params = [
            'apiOperation' => ApiOP::CREATE_CHECKOUT_SESSION,
            'interaction' => [
                'operation' => $operation ?? SessionOP::PURCHASE
            ],
            'order' => [
                'currency' => $currency,
                'id' => RandomGenerator::uuid()
            ]
        ];

        return self::create($params);
    }
}

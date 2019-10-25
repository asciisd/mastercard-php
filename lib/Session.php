<?php


namespace Mastercard;

use Mastercard\Enums\MastercardApiOperations as ApiOP;
use Mastercard\Enums\SessionInteractionOperations as SessionOP;
use Mastercard\Util\Factory;

/**
 * Class Session
 *
 * @property string $correlationId
 * @property string $merchant
 * @property string $result
 * @property Session $session
 * @property string $successIndicator
 * @property SourceOfFunds $sourceOfFunds
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

    public static function createCheckout(Factory $factory = null)
    {
        $factory->apiOperation(ApiOP::CREATE_CHECKOUT_SESSION)
            ->interaction(SessionOP::PURCHASE);

        return self::create($factory->get());
    }
}

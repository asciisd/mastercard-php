<?php


namespace Mastercard;

/**
 * Class Card
 *
 * @property string $accountType
 * @property MastercardObject $expiry
 * @property string $nameOnCard
 * @property string $number
 * @property string $securityCode
 *
 * @package Mastercard
 */
class Card extends ApiResource
{
    const OBJECT_NAME = 'card';
}

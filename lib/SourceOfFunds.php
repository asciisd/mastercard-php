<?php


namespace Mastercard;

/**
 * Class sourceOfFunds
 *
 * @property SourceOfFundsProvided $provided
 * @property string $token
 * @property string $tokenRequestorID
 * @property string $type
 *
 * @package Mastercard
 */
class SourceOfFunds extends ApiResource
{
    const OBJECT_NAME = 'sourceOfFunds';
}

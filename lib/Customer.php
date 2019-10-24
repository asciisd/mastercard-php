<?php


namespace Mastercard;

/**
 * Class Customer
 *
 * @property string $dateOfBirth
 * @property string $email
 * @property string $firstName
 * @property string $lastName
 * @property string $mobilePhone
 * @property string $nationalId
 * @property string $phone
 * @property string $taxRegistrationId
 *
 * @package Mastercard
 */
class Customer extends ApiResource
{
    const OBJECT_NAME = 'customer';
}

<?php

namespace Mastercard\Exception\OAuth;

/**
 * InvalidRequestException is thrown when a code, refresh token, or grant
 * type parameter is not provided, but was required.
 *
 * @package Mastercard\Exception\OAuth
 */
class InvalidRequestException extends OAuthErrorException
{
}

<?php

namespace Mastercard\Exception\OAuth;

/**
 * Implements properties and methods common to all (non-SPL) Mastercard OAuth
 * exceptions.
 */
abstract class OAuthErrorException extends \Mastercard\Exception\ApiErrorException
{
    protected function constructErrorObject()
    {
        if (is_null($this->jsonBody)) {
            return null;
        }

        return \Mastercard\OAuthErrorObject::constructFrom($this->jsonBody);
    }
}

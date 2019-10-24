<?php

namespace Mastercard\Exception;

/**
 * ApiConnection is thrown in the event that the SDK can't connect to Mastercard's
 * servers. That can be for a variety of different reasons from a downed
 * network to a bad TLS certificate.
 *
 * @package Mastercard\Exception
 */
class ApiConnectionException extends ApiErrorException
{
}

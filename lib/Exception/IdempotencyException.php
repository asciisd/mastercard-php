<?php

namespace Mastercard\Exception;

/**
 * IdempotencyException is thrown in cases where an idempotency key was used
 * improperly.
 *
 * @package Mastercard\Exception
 */
class IdempotencyException extends ApiErrorException
{
}

<?php

namespace Mastercard\Exception;

/**
 * InvalidRequestException is thrown when a request is initiated with invalid
 * parameters.
 *
 * @package Mastercard\Exception
 */
class InvalidRequestException extends ApiErrorException
{
    protected $mastercardParam;

    /**
     * Creates a new InvalidRequestException exception.
     *
     * @param string $message The exception message.
     * @param int|null $httpStatus The HTTP status code.
     * @param string|null $httpBody The HTTP body as a string.
     * @param array|null $jsonBody The JSON deserialized body.
     * @param array|\Mastercard\Util\CaseInsensitiveArray|null $httpHeaders The HTTP headers array.
     * @param string|null $mastercardCode The Mastercard error code.
     * @param string|null $mastercardParam The parameter related to the error.
     *
     * @return InvalidRequestException
     */
    public static function factory(
        $message,
        $httpStatus = null,
        $httpBody = null,
        $jsonBody = null,
        $httpHeaders = null,
        $mastercardCode = null,
        $mastercardParam = null
    ) {
        $instance = parent::factory($message, $httpStatus, $httpBody, $jsonBody, $httpHeaders, $mastercardCode);
        $instance->setMastercardParam($mastercardParam);

        return $instance;
    }

    /**
     * Gets the parameter related to the error.
     *
     * @return string|null
     */
    public function getMastercardParam()
    {
        return $this->mastercardParam;
    }

    /**
     * Sets the parameter related to the error.
     *
     * @param string|null $mastercardParam
     */
    public function setMastercardParam($mastercardParam)
    {
        $this->mastercardParam = $mastercardParam;
    }
}

<?php

namespace Mastercard\Exception;

/**
 * CardException is thrown when a user enters a card that can't be charged for
 * some reason.
 *
 * @package Mastercard\Exception
 */
class CardException extends ApiErrorException
{
    protected $declineCode;
    protected $mastercardParam;

    /**
     * Creates a new CardException exception.
     *
     * @param string $message The exception message.
     * @param int|null $httpStatus The HTTP status code.
     * @param string|null $httpBody The HTTP body as a string.
     * @param array|null $jsonBody The JSON deserialized body.
     * @param array|\Mastercard\Util\CaseInsensitiveArray|null $httpHeaders The HTTP headers array.
     * @param string|null $mastercardCode The Mastercard error code.
     * @param string|null $declineCode The decline code.
     * @param string|null $mastercardParam The parameter related to the error.
     *
     * @return CardException
     */
    public static function factory(
        $message,
        $httpStatus = null,
        $httpBody = null,
        $jsonBody = null,
        $httpHeaders = null,
        $mastercardCode = null,
        $declineCode = null,
        $mastercardParam = null
    ) {
        $instance = parent::factory($message, $httpStatus, $httpBody, $jsonBody, $httpHeaders, $mastercardCode);
        $instance->setDeclineCode($declineCode);
        $instance->setMastercardParam($mastercardParam);

        return $instance;
    }

    /**
     * Gets the decline code.
     *
     * @return string|null
     */
    public function getDeclineCode()
    {
        return $this->declineCode;
    }

    /**
     * Sets the decline code.
     *
     * @param string|null $declineCode
     */
    public function setDeclineCode($declineCode)
    {
        $this->declineCode = $declineCode;
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

<?php

namespace Mastercard\Exception;

/**
 * Implements properties and methods common to all (non-SPL) Mastercard exceptions.
 */
abstract class ApiErrorException extends \Exception implements ExceptionInterface
{
    protected $error;
    protected $httpBody;
    protected $httpHeaders;
    protected $httpStatus;
    protected $jsonBody;
    protected $requestId;
    protected $mastercardCode;

    /**
     * Creates a new API error exception.
     *
     * @param string $message The exception message.
     * @param int|null $httpStatus The HTTP status code.
     * @param string|null $httpBody The HTTP body as a string.
     * @param array|null $jsonBody The JSON deserialized body.
     * @param array|\Mastercard\Util\CaseInsensitiveArray|null $httpHeaders The HTTP headers array.
     * @param string|null $mastercardCode The Mastercard error code.
     *
     * @return static
     */
    public static function factory(
        $message,
        $httpStatus = null,
        $httpBody = null,
        $jsonBody = null,
        $httpHeaders = null,
        $mastercardCode = null
    ) {
        $instance = new static($message);
        $instance->setHttpStatus($httpStatus);
        $instance->setHttpBody($httpBody);
        $instance->setJsonBody($jsonBody);
        $instance->setHttpHeaders($httpHeaders);
        $instance->setMastercardCode($mastercardCode);

        $instance->setRequestId(null);
        if ($httpHeaders && isset($httpHeaders['Request-Id'])) {
            $instance->setRequestId($httpHeaders['Request-Id']);
        }

        $instance->setError($instance->constructErrorObject());

        return $instance;
    }

    /**
     * Gets the Mastercard error object.
     *
     * @return \Mastercard\ErrorObject|null
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * Sets the Mastercard error object.
     *
     * @param \Mastercard\ErrorObject|null $error
     */
    public function setError($error)
    {
        $this->error = $error;
    }

    /**
     * Gets the HTTP body as a string.
     *
     * @return string|null
     */
    public function getHttpBody()
    {
        return $this->httpBody;
    }

    /**
     * Sets the HTTP body as a string.
     *
     * @param string|null $httpBody
     */
    public function setHttpBody($httpBody)
    {
        $this->httpBody = $httpBody;
    }

    /**
     * Gets the HTTP headers array.
     *
     * @return array|\Mastercard\Util\CaseInsensitiveArray|null
     */
    public function getHttpHeaders()
    {
        return $this->httpHeaders;
    }

    /**
     * Sets the HTTP headers array.
     *
     * @param array|\Mastercard\Util\CaseInsensitiveArray|null $httpHeaders
     */
    public function setHttpHeaders($httpHeaders)
    {
        $this->httpHeaders = $httpHeaders;
    }

    /**
     * Gets the HTTP status code.
     *
     * @return int|null
     */
    public function getHttpStatus()
    {
        return $this->httpStatus;
    }

    /**
     * Sets the HTTP status code.
     *
     * @param int|null $httpStatus
     */
    public function setHttpStatus($httpStatus)
    {
        $this->httpStatus = $httpStatus;
    }

    /**
     * Gets the JSON deserialized body.
     *
     * @return array|null
     */
    public function getJsonBody()
    {
        return $this->jsonBody;
    }

    /**
     * Sets the JSON deserialized body.
     *
     * @param array|null $jsonBody
     */
    public function setJsonBody($jsonBody)
    {
        $this->jsonBody = $jsonBody;
    }

    /**
     * Gets the Mastercard request ID.
     *
     * @return string|null
     */
    public function getRequestId()
    {
        return $this->requestId;
    }

    /**
     * Sets the Mastercard request ID.
     *
     * @param string|null $requestId
     */
    public function setRequestId($requestId)
    {
        $this->requestId = $requestId;
    }

    /**
     * Gets the Mastercard error code.
     *
     * Cf. the `CODE_*` constants on {@see \Mastercard\ErrorObject} for possible
     * values.
     *
     * @return string|null
     */
    public function getMastercardCode()
    {
        return $this->mastercardCode;
    }

    /**
     * Sets the Mastercard error code.
     *
     * @param string|null $mastercardCode
     */
    public function setMastercardCode($mastercardCode)
    {
        $this->mastercardCode = $mastercardCode;
    }

    /**
     * Returns the string representation of the exception.
     *
     * @return string
     */
    public function __toString()
    {
        $statusStr = ($this->getHttpStatus() == null) ? "" : "(Status {$this->getHttpStatus()}) ";
        $idStr = ($this->getRequestId() == null) ? "" : "(Request {$this->getRequestId()}) ";
        return "{$statusStr}{$idStr}{$this->getMessage()}";
    }

    protected function constructErrorObject()
    {
        if (is_null($this->jsonBody) || !array_key_exists('error', $this->jsonBody)) {
            return null;
        }

        return \Mastercard\ErrorObject::constructFrom($this->jsonBody['error']);
    }
}

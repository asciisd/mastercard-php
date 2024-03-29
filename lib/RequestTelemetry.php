<?php

namespace Mastercard;

/**
 * Class RequestTelemetry
 *
 * Tracks client request telemetry
 * @package Mastercard
 */
class RequestTelemetry
{
    public $requestId;
    public $requestDuration;

    /**
     * Initialize a new telemetry object.
     *
     * @param string $requestId The request's request ID.
     * @param int $requestDuration The request's duration in milliseconds.
     */
    public function __construct($requestId, $requestDuration)
    {
        $this->requestId = $requestId;
        $this->requestDuration = $requestDuration;
    }
}

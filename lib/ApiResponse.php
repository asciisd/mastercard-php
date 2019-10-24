<?php

namespace Mastercard;

use Mastercard\Util\CaseInsensitiveArray;

/**
 * Class ApiResponse
 *
 * @package Mastercard
 */
class ApiResponse
{
    public $headers;
    public $body;
    public $json;
    public $code;

    /**
     * @param string $body
     * @param integer $code
     * @param array|CaseInsensitiveArray|null $headers
     * @param array|null $json
     */
    public function __construct($body, $code, $headers, $json)
    {
        $this->body = $body;
        $this->code = $code;
        $this->headers = $headers;
        $this->json = $json;
    }
}

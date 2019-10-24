<?php


namespace Mastercard;


class Mastercard
{
    const REGION_ASIA_PACIFIC = 'ASIA_PACIFIC';
    const REGION_EUROPE = 'EUROPE';
    const REGION_NORTH_AMERICA = 'NORTH_AMERICA';
    const REGION_MTF = 'MTF';
    const REGION_QA01 = 'QA01';
    const VERSION = '1.0.2';


    // @var string the api password to be used for requests.
    public static $apiKey;

    // @var string the Merchant ID to be used for requests.
    public static $merchantId;

    // @var string for region to be used for requests.
    public static $region = self::REGION_ASIA_PACIFIC;

    public static $apiVersion = 53;

    // @var string Path to the CA bundle used to verify SSL certificates
    public static $caBundlePath = null;

    // @var boolean Defaults to true.
    public static $verifySslCerts = true;

    // @var array The application's information (name, version, URL)
    public static $appInfo = null;

    // @var Util\LoggerInterface|null The logger to which the library will
    //   produce messages.
    public static $logger = null;

    // @var int Maximum number of request retries
    public static $maxNetworkRetries = 0;

    // @var boolean Whether client telemetry is enabled. Defaults to true.
    public static $enableTelemetry = false;

    // @var float Maximum delay between retries, in seconds
    private static $maxNetworkRetryDelay = 2.0;

    // @var float Maximum delay between retries, in seconds, that will be respected from the Mastercard API
    private static $maxRetryAfter = 60.0;

    // @var float Initial delay between retries, in seconds
    private static $initialNetworkRetryDelay = 0.5;

    /**
     * @return string
     */
    public static function getMerchantId()
    {
        return self::$merchantId;
    }

    /**
     * @param string $merchantId
     */
    public static function setMerchantId($merchantId)
    {
        self::$merchantId = $merchantId;
    }

    /**
     * @return Util\LoggerInterface The logger to which the library will
     *   produce messages.
     */
    public static function getLogger()
    {
        if (self::$logger == null) {
            return new Util\DefaultLogger();
        }
        return self::$logger;
    }

    /**
     * @param Util\LoggerInterface $logger The logger to which the library
     *   will produce messages.
     */
    public static function setLogger($logger)
    {
        self::$logger = $logger;
    }

    /**
     * @return boolean
     */
    public static function getVerifySslCerts()
    {
        return self::$verifySslCerts;
    }

    /**
     * @param boolean $verify
     */
    public static function setVerifySslCerts($verify)
    {
        self::$verifySslCerts = $verify;
    }

    /**
     * @return string The API version used for requests. null if we're using the
     *    latest version.
     */
    public static function getApiVersion()
    {
        return self::$apiVersion;
    }

    /**
     * @param string $apiVersion The API version to use for requests.
     */
    public static function setApiVersion($apiVersion)
    {
        self::$apiVersion = $apiVersion;
    }

    /**
     * @return string
     */
    private static function getDefaultCABundlePath()
    {
        return realpath(dirname(__FILE__) . '/../data/ca-certificates.crt');
    }

    /**
     * @return string
     */
    public static function getCABundlePath()
    {
        return self::$caBundlePath ?: self::getDefaultCABundlePath();
    }

    /**
     * @return array | null The application's information
     */
    public static function getAppInfo()
    {
        return self::$appInfo;
    }

    /**
     * @param string $appName The application's name
     * @param string $appVersion The application's version
     * @param string $appUrl The application's URL
     * @param null $appPartnerId
     */
    public static function setAppInfo($appName, $appVersion = null, $appUrl = null, $appPartnerId = null)
    {
        self::$appInfo = self::$appInfo ?: [];
        self::$appInfo['name'] = $appName;
        self::$appInfo['partner_id'] = $appPartnerId;
        self::$appInfo['url'] = $appUrl;
        self::$appInfo['version'] = $appVersion;
    }

    /**
     * @return int Maximum number of request retries
     */
    public static function getMaxNetworkRetries()
    {
        return self::$maxNetworkRetries;
    }

    /**
     * @param int $maxNetworkRetries Maximum number of request retries
     */
    public static function setMaxNetworkRetries($maxNetworkRetries)
    {
        self::$maxNetworkRetries = $maxNetworkRetries;
    }

    /**
     * @return float Maximum delay between retries, in seconds
     */
    public static function getMaxNetworkRetryDelay()
    {
        return self::$maxNetworkRetryDelay;
    }

    /**
     * @return float Maximum delay between retries, in seconds, that will be respected from the Mastercard API
     */
    public static function getMaxRetryAfter()
    {
        return self::$maxRetryAfter;
    }

    /**
     * @return float Initial delay between retries, in seconds
     */
    public static function getInitialNetworkRetryDelay()
    {
        return self::$initialNetworkRetryDelay;
    }

    /**
     * @return bool Whether client telemetry is enabled
     */
    public static function getEnableTelemetry()
    {
        return self::$enableTelemetry;
    }

    /**
     * @param bool $enableTelemetry Enables client telemetry.
     *
     * Client telemetry enables timing and request metrics to be sent back to Mastercard as an HTTP Header
     * with the current request. This enables Mastercard to do latency and metrics analysis without adding extra
     * overhead (such as extra network calls) on the client.
     */
    public static function setEnableTelemetry($enableTelemetry)
    {
        self::$enableTelemetry = $enableTelemetry;
    }

    public static function getGatewayUrl()
    {
        // get regional url prefix
        $prefix = 'test-';
        if (strcasecmp(self::$region, "ASIA_PACIFIC") == 0) {
            $prefix = 'ap-';
        } else if (strcasecmp(self::$region, "EUROPE") == 0) {
            $prefix = 'eu-';
        } else if (strcasecmp(self::$region, "NORTH_AMERICA") == 0) {
            $prefix = 'na-';
        } else if (strcasecmp(self::$region, "MTF") == 0) {
            $prefix = 'test-';
        } else if (strcasecmp(self::$region, "QA01") == 0) {
            $prefix = 'qa01.';
        } else {
            throw new Exception\InvalidArgumentException("Invalid region provided. Valid values include ASIA_PACIFIC, EUROPE, NORTH_AMERICA, MTF", 400);
        }

        $apiVersion = self::validateApiVersion();
        $merchantId = self::validateMerchantId();

        return "https://${prefix}gateway.mastercard.com/api/rest/version/${apiVersion}/merchant/${merchantId}";
    }

    private static function validateApiVersion() {
        // validate apiVersion is above minimum
        if (intval(self::$apiVersion) < 39) {
            throw new Exception\InvalidArgumentException("API Version must be >= 39", 400);
        }

        return self::$apiVersion;
    }

    private static function validateMerchantId() {
        // make your validations before return the $merchantId
        return self::$merchantId;
    }

    /**
     * @return mixed
     */
    public static function getApiKey()
    {
        return self::$apiKey;
    }

    /**
     * @param mixed $apiKey
     */
    public static function setApiKey($apiKey)
    {
        self::$apiKey = $apiKey;
    }
}

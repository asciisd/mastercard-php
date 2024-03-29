<?php

namespace Mastercard\HttpClient;

use Mastercard\Mastercard;
use ReflectionClass;
use Mastercard\TestCase;

class CurlClientTest extends TestCase
{
    /**
     * @before
     */
    public function saveOriginalNetworkValues()
    {
        $this->origMaxNetworkRetries = Mastercard::getMaxNetworkRetries();
        $this->origMaxNetworkRetryDelay = Mastercard::getMaxNetworkRetryDelay();
        $this->origInitialNetworkRetryDelay = Mastercard::getInitialNetworkRetryDelay();
    }

    /**
     * @before
     */
    public function setUpReflectors()
    {
        $mastercardReflector = new ReflectionClass('\Mastercard\Mastercard');

        $this->maxNetworkRetryDelayProperty = $mastercardReflector->getProperty('maxNetworkRetryDelay');
        $this->maxNetworkRetryDelayProperty->setAccessible(true);

        $this->initialNetworkRetryDelayProperty = $mastercardReflector->getProperty('initialNetworkRetryDelay');
        $this->initialNetworkRetryDelayProperty->setAccessible(true);

        $curlClientReflector = new ReflectionClass('Mastercard\HttpClient\CurlClient');

        $this->shouldRetryMethod = $curlClientReflector->getMethod('shouldRetry');
        $this->shouldRetryMethod->setAccessible(true);

        $this->sleepTimeMethod = $curlClientReflector->getMethod('sleepTime');
        $this->sleepTimeMethod->setAccessible(true);
    }

    /**
     * @after
     */
    public function restoreOriginalNetworkValues()
    {
        Mastercard::setMaxNetworkRetries($this->origMaxNetworkRetries);
        $this->setMaxNetworkRetryDelay($this->origMaxNetworkRetryDelay);
        $this->setInitialNetworkRetryDelay($this->origInitialNetworkRetryDelay);
    }

    private function setMaxNetworkRetryDelay($maxNetworkRetryDelay)
    {
        $this->maxNetworkRetryDelayProperty->setValue(null, $maxNetworkRetryDelay);
    }

    private function setInitialNetworkRetryDelay($initialNetworkRetryDelay)
    {
        $this->initialNetworkRetryDelayProperty->setValue(null, $initialNetworkRetryDelay);
    }

    private function createFakeRandomGenerator($returnValue = 1.0)
    {
        $fakeRandomGenerator = $this->createMock('Mastercard\Util\RandomGenerator', ['randFloat']);
        $fakeRandomGenerator->method('randFloat')->willReturn($returnValue);
        return $fakeRandomGenerator;
    }

    public function testTimeout()
    {
        $curl = new CurlClient();
        $this->assertSame(CurlClient::DEFAULT_TIMEOUT, $curl->getTimeout());
        $this->assertSame(CurlClient::DEFAULT_CONNECT_TIMEOUT, $curl->getConnectTimeout());

        // implicitly tests whether we're returning the CurlClient instance
        $curl = $curl->setConnectTimeout(1)->setTimeout(10);
        $this->assertSame(1, $curl->getConnectTimeout());
        $this->assertSame(10, $curl->getTimeout());

        $curl->setTimeout(-1);
        $curl->setConnectTimeout(-999);
        $this->assertSame(0, $curl->getTimeout());
        $this->assertSame(0, $curl->getConnectTimeout());
    }

    public function testUserAgentInfo()
    {
        $curl = new CurlClient();
        $uaInfo = $curl->getUserAgentInfo();
        $this->assertNotNull($uaInfo);
        $this->assertNotNull($uaInfo['httplib']);
        $this->assertNotNull($uaInfo['ssllib']);
    }

    public function testDefaultOptions()
    {
        // make sure options array loads/saves properly
        $optionsArray = [CURLOPT_PROXY => 'localhost:80'];
        $withOptionsArray = new CurlClient($optionsArray);
        $this->assertSame($withOptionsArray->getDefaultOptions(), $optionsArray);

        // make sure closure-based options work properly, including argument passing
        $ref = null;
        $withClosure = new CurlClient(function ($method, $absUrl, $headers, $params, $hasFile) use (&$ref) {
            $ref = func_get_args();
            return [];
        });

        $withClosure->request('get', 'https://httpbin.org/status/200', [], [], false);
        $this->assertSame($ref, ['get', 'https://httpbin.org/status/200', [], [], false]);

        // this is the last test case that will run, since it'll throw an exception at the end
        $withBadClosure = new CurlClient(function () {
            return 'thisShouldNotWork';
        });
        $this->expectException('Mastercard\Exception\UnexpectedValueException', "Non-array value returned by defaultOptions CurlClient callback");
        $withBadClosure->request('get', 'https://httpbin.org/status/200', [], [], false);
    }

    public function testSslOption()
    {
        // make sure options array loads/saves properly
        $optionsArray = [CURLOPT_SSLVERSION => CURL_SSLVERSION_TLSv1];
        $withOptionsArray = new CurlClient($optionsArray);
        $this->assertSame($withOptionsArray->getDefaultOptions(), $optionsArray);
    }

    public function testShouldRetryOnTimeout()
    {
        Mastercard::setMaxNetworkRetries(2);

        $curlClient = new CurlClient();

        $this->assertTrue($this->shouldRetryMethod->invoke($curlClient, CURLE_OPERATION_TIMEOUTED, 0, [], 0));
    }

    public function testShouldRetryOnConnectionFailure()
    {
        Mastercard::setMaxNetworkRetries(2);

        $curlClient = new CurlClient();

        $this->assertTrue($this->shouldRetryMethod->invoke($curlClient, CURLE_COULDNT_CONNECT, 0, [], 0));
    }

    public function testShouldRetryOnConflict()
    {
        Mastercard::setMaxNetworkRetries(2);

        $curlClient = new CurlClient();

        $this->assertTrue($this->shouldRetryMethod->invoke($curlClient, 0, 409, [], 0));
    }


    public function testShouldNotRetryOn429()
    {
        Mastercard::setMaxNetworkRetries(2);

        $curlClient = new CurlClient();

        $this->assertFalse($this->shouldRetryMethod->invoke($curlClient, 0, 429, [], 0));
    }

    public function testShouldRetryOn500()
    {
        Mastercard::setMaxNetworkRetries(2);

        $curlClient = new CurlClient();

        $this->assertTrue($this->shouldRetryMethod->invoke($curlClient, 0, 500, [], 0));
    }

    public function testShouldRetryOn503()
    {
        Mastercard::setMaxNetworkRetries(2);

        $curlClient = new CurlClient();

        $this->assertTrue($this->shouldRetryMethod->invoke($curlClient, 0, 503, [], 0));
    }

    public function testShouldRetryOnMastercardShouldRetryTrue()
    {
        Mastercard::setMaxNetworkRetries(2);

        $curlClient = new CurlClient();

        $this->assertFalse($this->shouldRetryMethod->invoke($curlClient, 0, 400, [], 0));
        $this->assertTrue($this->shouldRetryMethod->invoke($curlClient, 0, 400, ['mastercard-should-retry' => 'true'], 0));
    }

    public function testShouldNotRetryOnMastercardShouldRetryFalse()
    {
        Mastercard::setMaxNetworkRetries(2);

        $curlClient = new CurlClient();

        $this->assertTrue($this->shouldRetryMethod->invoke($curlClient, 0, 500, [], 0));
        $this->assertFalse($this->shouldRetryMethod->invoke($curlClient, 0, 500, ['mastercard-should-retry' => 'false'], 0));
    }

    public function testShouldNotRetryAtMaximumCount()
    {
        Mastercard::setMaxNetworkRetries(2);

        $curlClient = new CurlClient();

        $this->assertFalse($this->shouldRetryMethod->invoke($curlClient, 0, 0, [], Mastercard::getMaxNetworkRetries()));
    }

    public function testShouldNotRetryOnCertValidationError()
    {
        Mastercard::setMaxNetworkRetries(2);

        $curlClient = new CurlClient();

        $this->assertFalse($this->shouldRetryMethod->invoke($curlClient, CURLE_SSL_PEER_CERTIFICATE, -1, [], 0));
    }

    public function testSleepTimeShouldGrowExponentially()
    {
        $this->setMaxNetworkRetryDelay(999);

        $curlClient = new CurlClient(null, $this->createFakeRandomGenerator());

        $this->assertEquals(
            Mastercard::getInitialNetworkRetryDelay() * 1,
            $this->sleepTimeMethod->invoke($curlClient, 1, [])
        );
        $this->assertEquals(
            Mastercard::getInitialNetworkRetryDelay() * 2,
            $this->sleepTimeMethod->invoke($curlClient, 2, [])
        );
        $this->assertEquals(
            Mastercard::getInitialNetworkRetryDelay() * 4,
            $this->sleepTimeMethod->invoke($curlClient, 3, [])
        );
        $this->assertEquals(
            Mastercard::getInitialNetworkRetryDelay() * 8,
            $this->sleepTimeMethod->invoke($curlClient, 4, [])
        );
    }

    public function testSleepTimeShouldEnforceMaxNetworkRetryDelay()
    {
        $this->setInitialNetworkRetryDelay(1);
        $this->setMaxNetworkRetryDelay(2);

        $curlClient = new CurlClient(null, $this->createFakeRandomGenerator());

        $this->assertEquals(1, $this->sleepTimeMethod->invoke($curlClient, 1, []));
        $this->assertEquals(2, $this->sleepTimeMethod->invoke($curlClient, 2, []));
        $this->assertEquals(2, $this->sleepTimeMethod->invoke($curlClient, 3, []));
        $this->assertEquals(2, $this->sleepTimeMethod->invoke($curlClient, 4, []));
    }

    public function testSleepTimeShouldRespectRetryAfter()
    {
        $this->setInitialNetworkRetryDelay(1);
        $this->setMaxNetworkRetryDelay(2);

        $curlClient = new CurlClient(null, $this->createFakeRandomGenerator());

        // Uses max of default and header.
        $this->assertEquals(10, $this->sleepTimeMethod->invoke($curlClient, 1, ['retry-after' => '10']));
        $this->assertEquals(2, $this->sleepTimeMethod->invoke($curlClient, 2, ['retry-after' => '1']));

        // Ignores excessively large values.
        $this->assertEquals(2, $this->sleepTimeMethod->invoke($curlClient, 2, ['retry-after' => '100']));
    }

    public function testSleepTimeShouldAddSomeRandomness()
    {
        $randomValue = 0.8;
        $this->setInitialNetworkRetryDelay(1);
        $this->setMaxNetworkRetryDelay(8);

        $curlClient = new CurlClient(null, $this->createFakeRandomGenerator($randomValue));

        $baseValue = Mastercard::getInitialNetworkRetryDelay() * (0.5 * (1 + $randomValue));

        // the initial value cannot be smaller than the base,
        // so the randomness is ignored
        $this->assertEquals(Mastercard::getInitialNetworkRetryDelay(), $this->sleepTimeMethod->invoke($curlClient, 1, []));

        // after the first one, the randomness is applied
        $this->assertEquals($baseValue * 2, $this->sleepTimeMethod->invoke($curlClient, 2, []));
        $this->assertEquals($baseValue * 4, $this->sleepTimeMethod->invoke($curlClient, 3, []));
        $this->assertEquals($baseValue * 8, $this->sleepTimeMethod->invoke($curlClient, 4, []));
    }

    public function testResponseHeadersCaseInsensitive()
    {
//        $customers = Customer::all();
//        $headers = $customers->getLastResponse()->headers;
//        $this->assertNotNull($headers);
    }
}

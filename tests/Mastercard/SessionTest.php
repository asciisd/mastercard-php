<?php


namespace Mastercard;


use Mastercard\Enums\Currency;
use Mastercard\Util\Factory;

class SessionTest extends TestCase
{
    const TEST_RESOURCE_ID = 'SESSION0002590836528N74609262L0';

    public function testIsCreatable()
    {
        $this->expectsRequest(
            'post',
            '/session'
        );

        $resource = Session::create();

        $this->assertInstanceOf(Session::class, $resource);
        self::assertEquals('SUCCESS', $resource->result);
        $this->assertNotNull($resource->session->id);
    }

    public function testIsCheckoutCreatable()
    {
        $this->expectsRequest(
            'post',
            '/session'
        );

        $factory = Factory::create()->order(null, Currency::KWD);
        $resource = Session::createCheckout($factory);

        $this->assertInstanceOf(Session::class, $resource);
        self::assertEquals('SUCCESS', $resource->result);
        $this->assertNotNull($resource->session->id);
    }

    public function testIsRetrievable()
    {
        $session = Session::create();
        $this->expectsRequest(
            'get',
            '/session/' . $session->session->id
        );

        $resource = Session::retrieve($session->session->id);
        $this->assertInstanceOf(Session::class, $resource);
    }

    public function testIsUpdatable()
    {
        $resource = Session::create();
        $this->expectsRequest(
            'put',
            '/session/' . $resource->session->id
        );

        $resource = Session::update($resource->session->id,
            Factory::create()
                ->order('100', 'KWD')
                ->customer('Amr', 'Ahmed', 'aemaddin@gmail.com')
                ->card('5123450000000008', 'AMR AHMED', '05', '21')
                ->paymentType()
                ->get()
        );

        $this->assertInstanceOf(Session::class, $resource);
    }
}

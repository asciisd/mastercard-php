<?php


namespace Mastercard\Mastercard;


use Mastercard\Enums\Currency;
use Mastercard\Enums\MastercardApiOperations as ApiOp;
use Mastercard\Order;
use Mastercard\Session;
use Mastercard\TestCase;
use Mastercard\Transaction;
use Mastercard\Util\Factory;
use Mastercard\Util\RandomGenerator;

class OrderTest extends TestCase
{
    public function testIsRetrievable()
    {
        $trans_id = RandomGenerator::uuid();
        $order_id = RandomGenerator::uuid();
        $session = Session::create();
        $session = Session::update($session->session->id, Factory::create()
            ->card('5123450000000008', 'AMR AHMED', '05', '21', '100')
            ->get()
        );

        $factory = Factory::create()
            ->apiOperation(ApiOp::PAY)
            ->session($session->session->id)
            ->order(100, Currency::KWD, false)
            ->sourceOfFunds('CARD');

        Transaction::pay($trans_id, $order_id, $factory);

        $this->expectsRequest(
            'get',
            '/order/' . $order_id
        );

        $resource = Order::retrieve($order_id);
        $this->assertInstanceOf(Order::class, $resource);
    }
}

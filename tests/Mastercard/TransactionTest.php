<?php


namespace Mastercard\Mastercard;


use Mastercard\Enums\Currency;
use Mastercard\Enums\MastercardApiOperations as ApiOp;
use Mastercard\Session;
use Mastercard\TestCase;
use Mastercard\ThreeDS;
use Mastercard\Transaction;
use Mastercard\Util\Factory;
use Mastercard\Util\RandomGenerator;

class TransactionTest extends TestCase
{
    public function testIsPayable()
    {
        $trans_id = RandomGenerator::uuid();
        $order_id = RandomGenerator::uuid();
        $session = Session::create();
        $session = Session::update($session->session->id, Factory::create()
            ->card('5123450000000008', 'AMR EMADELDIN AHMED', '05', '21', '100')
            ->get()
        );

        $this->expectsRequest(
            'put',
            '/order/' . $order_id . '/transaction/' . $trans_id
        );

        $params = Factory::create()
            ->apiOperation(ApiOp::PAY)
            ->session($session->session->id)
            ->order(100, Currency::KWD, false)
            ->sourceOfFunds('CARD');

        $resource = Transaction::pay($trans_id, $order_id, $params);

        $this->assertInstanceOf(Transaction::class, $resource);
        self::assertEquals('SUCCESS', $resource->result);
    }

    public function testIsFailOnMissing3DS()
    {
        $trans_id = RandomGenerator::uuid();
        $order_id = RandomGenerator::uuid();
        $three_d_s = RandomGenerator::uuid();
        $session = Session::create();
        $session = Session::update($session->session->id, Factory::create()
            ->card('5123450000000008', 'AMR EMADELDIN AHMED', '05', '21', '100')
            ->get()
        );

        $threeDS = ThreeDS::checkEnrollment($three_d_s, Factory::create()
            ->session($session->session->id)
            ->threeDsRedirect('https://mastercard.test/threeds/callback')
            ->order(100, Currency::KWD, false)
        );

        $session = Session::update($session->session->id, Factory::create()
            ->threeDSecureId($threeDS->id())
            ->get()
        );

        $this->expectsRequest(
            'put',
            '/order/' . $order_id . '/transaction/' . $trans_id
        );

        $factory = Factory::create()
            ->session($session->session->id)
            ->order(100, Currency::KWD, false)
            ->sourceOfFunds('CARD');

        $resource = Transaction::pay($trans_id, $order_id, $factory);

        $this->assertInstanceOf(Transaction::class, $resource);
        self::assertEquals('FAILURE', $resource->result);
    }

    public function testIsRetrievable()
    {
        $trans_id = RandomGenerator::uuid();
        $order_id = RandomGenerator::uuid();
        $session = Session::create();
        $session = Session::update($session->session->id, Factory::create()
            ->card('5123450000000008', 'AMR EMADELDIN AHMED', '05', '21', '100')
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
            '/order/' . $order_id . '/transaction/' . $trans_id
        );

        $resource = Transaction::retrieve($order_id, null, $trans_id);
        $this->assertInstanceOf(Transaction::class, $resource);
    }

}

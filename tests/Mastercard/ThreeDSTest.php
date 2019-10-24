<?php


namespace Mastercard\Mastercard;


use Mastercard\Enums\Currency;
use Mastercard\Enums\MastercardApiOperations as ApiOP;
use Mastercard\Enums\ThreeDSPageModes as PageMode;
use Mastercard\Session;
use Mastercard\TestCase;
use Mastercard\ThreeDS;
use Mastercard\Util\Factory;

class ThreeDSTest extends TestCase
{
    const TEST_RESOURCE_ID = 'SESSION0002590836528N74609262L0';
    const PARES_ID = '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';
    const THREE_DS_ID = 'c480c57b-fce4-4f8f-a02d-78dbe9b20c7a';

    public function testIsRetrievable()
    {
        $this->expectsRequest(
            'get',
            '/3DSecureId/' . self::THREE_DS_ID
        );

        $resource = ThreeDS::retrieve(self::THREE_DS_ID);
        $this->assertInstanceOf(ThreeDS::class, $resource);
    }

    public function testCheckForEnrollment()
    {
        $resp = Session::create();
        $resp = Session::update($resp->session->id,
            Factory::create()
                ->order(10, Currency::KWD, false)
                ->customer('Amr', 'Ahmed', 'aemaddin@gmail.com')
                ->card('5123450000000008', 'AMR AHMED', '05', '21')
                ->get()
        );

        $this->expectsRequest(
            'put',
            '/3DSecureId/' . self::THREE_DS_ID
        );

        $factory = Factory::create()
            ->threeDsRedirect('https://mastercard.test/threeds/callback', PageMode::CUSTOMIZED)
            ->order('10', Currency::KWD, false)
            ->session($resp->session->id);

        $resource = ThreeDS::checkEnrollment(self::THREE_DS_ID, $factory);
        $this->assertInstanceOf(ThreeDS::class, $resource);
    }

    public function testProcessAcsResult()
    {
        $params = Factory::create()
            ->apiOperation(ApiOP::PROCESS_ACS_RESULT)
            ->processACS(self::PARES_ID)
            ->get();

        $this->expectsRequest(
            'post',
            '/3DSecureId/' . self::THREE_DS_ID
        );

        $resource = ThreeDS::processACS(self::THREE_DS_ID, $params);
        $this->assertInstanceOf(ThreeDS::class, $resource);
    }
}

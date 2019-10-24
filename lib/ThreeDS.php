<?php


namespace Mastercard;

use Mastercard\Enums\MastercardApiOperations as ApiOp;
use Mastercard\Util\Factory;

/**
 * Class ThreeDS
 *
 * @package Mastercard
 */
class ThreeDS extends ApiResource
{
    const OBJECT_NAME = "3DSecureId";
    const RETURN_OBJECT_NAME = "3DSecure";

    use ApiOperations\Retrieve;
    use ApiOperations\Update;

    /**
     * @param string $id The ID of the resource to update.
     * @param Factory $factory
     * @param array|string|null $opts
     *
     * @return array|\Mastercard\MastercardObject The updated resource.
     */
    public static function checkEnrollment($id, Factory $factory, $opts = null)
    {
        $params = $factory
            ->apiOperation(ApiOP::CHECK_3DS_ENROLLMENT)
            ->get();

        return static::update($id, $params, $opts);
    }

    /**
     * @param string $id The ID of the resource to update.
     * @param null $PaRES
     * @param array|string|null $opts
     *
     * @return array|MastercardObject
     */
    public static function processACS($id, $PaRES, $opts = null)
    {
        $factory = Factory::create()
            ->apiOperation(ApiOP::PROCESS_ACS_RESULT)
            ->processACS($PaRES);

        return self::postUpdate($id, $factory->get(), $opts);
    }

    public function acsUrl()
    {
        return $this['3DSecure']['authenticationRedirect']['customized']['acsUrl'] ?? '';
    }

    public function paReq()
    {
        return $this['3DSecure']['authenticationRedirect']['customized']['paReq'] ?? '';
    }

    public function gatewayRecommendation()
    {
        return $this['response']['gatewayRecommendation'] ?? '';
    }

    public function id()
    {
        return $this['3DSecureId'] ?? '';
    }

    public function merchant()
    {
        return $this['merchant'] ?? '';
    }

    public function veResEnrolled()
    {
        return $this['3DSecure']['veResEnrolled'] ?? '';
    }

    public function xid()
    {
        return $this['3DSecure']['xid'] ?? '';
    }

    public function authenticationToken()
    {
        return $this['3DSecure']['authenticationToken'] ?? '';
    }

    public function paResStatus()
    {
        return $this['3DSecure']['paResStatus'] ?? '';
    }

    public function acsEci()
    {
        return $this['3DSecure']['acsEci'] ?? '';
    }
}

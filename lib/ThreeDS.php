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
        $params = $factory->apiOperation(ApiOP::CHECK_3DS_ENROLLMENT)->get();

        return static::update($id, $params, $opts);
    }

    /**
     * @param string $id The ID of the resource to update.
     * @param array|null $params
     * @param array|string|null $opts
     *
     * @return array|\Mastercard\MastercardObject The updated resource.
     * @throws Exception\ApiErrorException
     */
    public static function processACS($id, $params = null, $opts = null)
    {
        self::_validateParams($params);
        $url = static::resourceUrl($id);
        list($response, $opts) = static::_staticRequest('post', $url, $params, $opts);
        $obj = \Mastercard\Util\Util::convertToMastercardObject($response->json, $opts);
        $obj->setLastResponse($response);
        return $obj;
    }

    public function acsUrl()
    {
        return $this['3DSecure']['authenticationRedirect']['customized']['acsUrl'];
    }

    public function paReq()
    {
        return $this['3DSecure']['authenticationRedirect']['customized']['paReq'];
    }

    public function gatewayRecommendation()
    {
        return $this['response']['gatewayRecommendation'];
    }

    public function id()
    {
        return $this['3DSecureId'];
    }

    public function merchant()
    {
        return $this['merchant'];
    }

    public function veResEnrolled()
    {
        return $this['3DSecure']['veResEnrolled'];
    }

    public function xid()
    {
        return $this['3DSecure']['xid'];
    }

    public function authenticationToken()
    {
        return $this['3DSecure']['authenticationToken'];
    }

    public function paResStatus()
    {
        return $this['3DSecure']['paResStatus'];
    }

    public function acsEci()
    {
        return $this['3DSecure']['acsEci'];
    }
}

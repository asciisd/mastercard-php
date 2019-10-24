<?php


namespace Mastercard\Util;


use Mastercard\Enums\Currency;
use Mastercard\Enums\MastercardApiOperations as ApiOP;
use Mastercard\Enums\PaymentTypes;
use Mastercard\Enums\ThreeDSPageModes as PageMode;

class ObjectBuilder
{
    protected $customer = [];
    protected $order = [];
    protected $card = [];
    protected $apiOperation = [];
    protected $threeDS = [];
    protected $threeDSecureId = [];
    protected $session = [];
    protected $processAcs = [];
    protected $billing = [];
    protected $sourceOfFunds = [];
    protected $paymentType = [];

    /**
     * @param $first_name
     * @param $last_name
     * @param $email
     * @param null $phone
     * @return ObjectBuilder
     */
    public function customer($first_name, $last_name, $email, $phone = null): ObjectBuilder
    {
        $this->customer = [
            'customer' => [
                'firstName' => $first_name,
                'lastName' => $last_name,
                'email' => $email
            ]
        ];

        if ($phone) {
            $this->customer['customer']['phone'] = $phone;
        }
        return $this;
    }

    /**
     * @param $country
     * @param null $city
     * @param null $state_province
     * @param null $street
     * @return ObjectBuilder
     */
    public function billing($country, $city = null, $state_province = null, $street = null): ObjectBuilder
    {
        $this->billing = [
            'billing' => [
                'address' => [
                    'country' => $country
                ]
            ]
        ];

        if ($city) {
            $this->billing['billing']['address']['city'] = $city;
        }

        if ($state_province) {
            $this->billing['billing']['address']['stateProvince'] = $state_province;
        }

        if ($street) {
            $this->billing['billing']['address']['street'] = $street;
        }

        return $this;
    }

    /**
     * @param $number
     * @param $name_on_card
     * @param $exp_month
     * @param $exp_year
     * @param null $security_code
     * @return ObjectBuilder
     */
    public function card($number, $name_on_card, $exp_month, $exp_year, $security_code = null): ObjectBuilder
    {
        $this->card = [
            'sourceOfFunds' => [
                'provided' => [
                    'card' => [
                        'expiry' => [
                            'month' => $exp_month,
                            'year' => $exp_year
                        ],
                        'nameOnCard' => $name_on_card,
                        'number' => $number
                    ]
                ]
            ]
        ];

        if ($security_code) {
            $this->card['sourceOfFunds']['provided']['card']['securityCode'] = $security_code;
        }

        return $this;
    }

    /**
     * @param $amount
     * @param string $currency
     * @param bool $autoId
     * @param array $params
     * @return ObjectBuilder
     */
    public function order($amount, $currency = null, $autoId = true, $params = []): ObjectBuilder
    {
        $this->order = [
            'order' => [
                'amount' => $amount,
                'currency' => $currency ?? Currency::USD
            ]
        ];

        if ($autoId) {
            $this->order['order']['id'] = RandomGenerator::uuid();
        }

        return $this;
    }

    /**
     * @param null $op
     * @return ObjectBuilder
     */
    public function apiOperation($op = null): ObjectBuilder
    {
        $this->apiOperation = [
            'apiOperation' => $op ?? ApiOP::CHECK_3DS_ENROLLMENT,
        ];

        return $this;
    }

    /**
     * @param $id
     * @return ObjectBuilder
     */
    public function threeDSecureId($id): ObjectBuilder
    {
        $this->threeDSecureId = [
            '3DSecureId' => $id
        ];

        return $this;
    }

    /**
     * @param $responseUrl
     * @param string $page_mode
     * @return ObjectBuilder
     */
    public function threeDsRedirect($responseUrl, $page_mode = null): ObjectBuilder
    {
        $this->threeDS = [
            '3DSecure' => [
                'authenticationRedirect' => [
                    'pageGenerationMode' => $page_mode ?? PageMode::CUSTOMIZED,
                    'responseUrl' => $responseUrl
                ]
            ]
        ];

        return $this;
    }

    /**
     * @param $paRes
     * @return ObjectBuilder
     */
    public function processACS($paRes): ObjectBuilder
    {
        $this->processAcs = [
            '3DSecure' => [
                'paRes' => $paRes
            ]
        ];

        return $this;
    }

    /**
     * @param $id
     * @param null $version
     * @return ObjectBuilder
     */
    public function session($id, $version = null): ObjectBuilder
    {
        $this->session = [
            'session' => [
                'id' => $id
            ]
        ];

        if ($version) $this->session['session']['version'] = $version;

        return $this;
    }

    /**
     * @param string $type
     * @return ObjectBuilder
     */
    public function sourceOfFunds($type): ObjectBuilder
    {
        $this->sourceOfFunds = [
            'sourceOfFunds' => [
                'type' => $type
            ]
        ];

        return $this;
    }

    /**
     * @param string $type
     * @return ObjectBuilder
     */
    public function paymentType($type = null): ObjectBuilder
    {
        $this->paymentType = [
            'paymentType' => $type ?? PaymentTypes::CARD
        ];

        return $this;
    }

    /**
     * return all filled data as array with mastercard api write format
     *
     * @return array
     */
    public function get(): array
    {
        $all_array = [];

        if (!empty($this->apiOperation)) {
            $all_array = array_merge($this->apiOperation, $all_array);
        }

        if (!empty($this->customer)) {
            $all_array = array_merge($this->customer, $all_array);
        }

        if (!empty($this->order)) {
            $all_array = array_merge($this->order, $all_array);
        }

        if (!empty($this->card) || !empty($this->sourceOfFunds)) {
            $all_array = array_merge($this->card, $this->sourceOfFunds, $all_array);
        }

        if (!empty($this->threeDS)) {
            $all_array = array_merge($this->threeDS, $all_array);
        }

        if (!empty($this->session)) {
            $all_array = array_merge($this->session, $all_array);
        }

        if (!empty($this->processAcs)) {
            $all_array = array_merge($this->processAcs, $all_array);
        }

        if (!empty($this->billing)) {
            $all_array = array_merge($this->billing, $all_array);
        }

        if (!empty($this->threeDSecureId)) {
            $all_array = array_merge($this->threeDSecureId, $all_array);
        }

        if (!empty($this->paymentType)) {
            $all_array = array_merge($this->paymentType, $all_array);
        }

        return $all_array;
    }


}

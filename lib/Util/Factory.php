<?php


namespace Mastercard\Util;


class Factory
{
    /**
     * @var ObjectBuilder
     */
    private $builder;


    public function __construct()
    {
        $this->builder = new ObjectBuilder();
    }

    /**
     * @return Factory
     */
    public static function create()
    {
        return new Factory();
    }

    /**
     * make customer with true array structure that accepted by mastercard api
     *
     * @param $first_name
     * @param $last_name
     * @param $email
     * @param null $phone
     * @param array $params
     * @return Factory
     */
    public function customer($first_name, $last_name, $email, $phone = null, $params = [])
    {
        $this->builder->customer($first_name, $last_name, $email, $phone, $params);

        return $this;
    }

    /**
     * make card with true array structure that accepted by mastercard api
     *
     * @param $number
     * @param $name_on_card
     * @param $exp_month
     * @param $exp_year
     * @param null $security_code
     * @return Factory
     */
    public function card($number, $name_on_card, $exp_month, $exp_year, $security_code = null)
    {
        $this->builder->card($number, $name_on_card, $exp_month, $exp_year, $security_code);

        return $this;
    }

    /**
     * make order with true array structure that accepted by mastercard api
     *
     * @param $amount
     * @param string $currency
     * @param bool $randomId
     * @param array $params
     * @return Factory
     */
    public function order($amount, $currency = null, $randomId = true, $params = [])
    {
        $this->builder->order($amount, $currency, $randomId, $params);

        return $this;
    }

    /**
     * make apiOperation with true array structure that accepted by mastercard api
     *
     * @param string $op
     * @return Factory
     */
    public function apiOperation($op = null)
    {
        $this->builder->apiOperation($op);

        return $this;
    }


    /**
     * inject 3DSecureId into mastercard api
     *
     * @param $id
     * @return Factory
     */
    public function threeDSecureId($id)
    {
        $this->builder->threeDSecureId($id);

        return $this;
    }

    /**
     * make 3DS with true array structure that accepted by mastercard api
     *
     * @param $responseUrl
     * @param string $page_mode
     * @return Factory
     */
    public function threeDsRedirect($responseUrl, $page_mode = null)
    {
        $this->builder->threeDsRedirect($responseUrl, $page_mode);

        return $this;
    }

    /**
     * make ACS Process with true array structure that accepted by mastercard api
     *
     * @param string $paRes
     * @return Factory
     */
    public function processACS($paRes)
    {
        $this->builder->processACS($paRes);

        return $this;
    }

    /**
     * make session with true array structure that accepted by mastercard api
     *
     * @param $id
     * @param null $version
     * @return Factory
     */
    public function session($id, $version = null)
    {
        $this->builder->session($id, $version);

        return $this;
    }

    /**
     * add sourceOfFund item to mastercard api array
     *
     * @param string $type
     * @return Factory
     */
    public function sourceOfFunds($type)
    {
        $this->builder->sourceOfFunds($type);

        return $this;
    }

    /**
     * add payment type to mastercard api array
     *
     * @param $type
     * @return Factory
     */
    public function paymentType($type = null)
    {
        $this->builder->paymentType($type);

        return $this;
    }

    /**
     * add billing information to mastercard api array
     *
     * @param $country
     * @param null $city
     * @param null $state_province
     * @param null $street
     * @return Factory
     */
    public function billing($country, $city = null, $state_province = null, $street = null)
    {
        $this->builder->billing($country, $city, $state_province, $street);

        return $this;
    }

    /**
     * add transaction id to mastercard api array
     *
     * @param $trans_id
     * @param $amount
     * @param $currency
     * @return Factory
     */
    public function transaction($trans_id = null, $amount = null, $currency = null)
    {
        $this->builder->transaction($trans_id, $amount, $currency);

        return $this;
    }

    /**
     * add operation to interaction
     *
     * @param string $operation
     * @return $this
     */
    public function interaction($operation)
    {
        $this->builder->interaction($operation);

        return $this;
    }

    /**
     * return all params as array with correct format that accepted by mastercard api
     *
     * @return array
     */
    public function get()
    {
        return $this->builder->get();
    }

    public function reset()
    {
        $this->builder = new ObjectBuilder();
    }
}

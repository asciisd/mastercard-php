<?php

namespace Mastercard;

/**
 * Class ErrorObject
 *
 * @property string $charge For card errors, the ID of the failed charge.
 * @property string $code For some errors that could be handled
 *    programmatically, a short string indicating the error code reported.
 * @property string $decline_code For card errors resulting from a card issuer
 *    decline, a short string indicating the card issuer's reason for the
 *    decline if they provide one.
 * @property string $doc_url A URL to more information about the error code
 *    reported.
 * @property string $message A human-readable message providing more details
 *    about the error. For card errors, these messages can be shown to your
 *    users.
 * @property string $param If the error is parameter-specific, the parameter
 *    related to the error. For example, you can use this to display a message
 *    near the correct form field.
 * @property PaymentIntent $payment_intent The PaymentIntent object for errors
 *    returned on a request involving a PaymentIntent.
 * @property PaymentMethod $payment_method The PaymentMethod object for errors
 *    returned on a request involving a PaymentMethod.
 * @property SetupIntent $setup_intent The SetupIntent object for errors
 *    returned on a request involving a SetupIntent.
 * @property MastercardObject $source The source object for errors returned on a
 *    request involving a source.
 * @property string $type The type of error returned. One of
 *    `api_connection_error`, `api_error`, `authentication_error`,
 *    `card_error`, `idempotency_error`, `invalid_request_error`, or
 *    `rate_limit_error`.
 *
 * @package Mastercard
 */
class ErrorObject extends MastercardObject
{
    /**
     * Possible string representations of an error's code.
     * @link https://mastercard.com/docs/error-codes
     */
    const CODE_1100 = 'Header values are missing';
    const CODE_1101 = 'Secret API Key and Environment are mismatched';
    const CODE_1102 = 'Request values are empty';
    const CODE_1103 = 'Required inputs are invalid';
    const CODE_1104 = 'Customer id is missing';
    const CODE_1105 = 'Customer id is invalid';
    const CODE_1106 = 'Customer not found';
    const CODE_1107 = 'Customer id is not matching with existing customer';
    const CODE_1108 = 'Save card features are not enabled';
    const CODE_1109 = 'Non 3D secure transactions are not allowed';
    const CODE_1110 = 'Redirect url is missing';
    const CODE_1111 = 'Redirect url is invalid';
    const CODE_1112 = 'Authorize id is missing';
    const CODE_1113 = 'Authorize id is invalid';
    const CODE_1114 = 'Please check the Authorize status';
    const CODE_1115 = 'Authorize not found';
    const CODE_1116 = 'Save card feature not supported for this transaction 1117 Amount is invalid';
    const CODE_1118 = 'Currency code is invalid';
    const CODE_1119 = 'Currency code not supported';
    const CODE_1120 = 'Statement descriptor is invalid or length should be less than 60 characters';
    const CODE_1121 = 'Description should be less than 1000 characters';
    const CODE_1122 = 'Merchant order reference length should be less than 100 characters';
    const CODE_1123 = 'Merchant transaction reference length should be less than 100 characters';
    const CODE_1124 = 'Source id is missing';
    const CODE_1125 = 'Source id is invalid';
    const CODE_1126 = 'Source already used, Please create the new source';
    const CODE_1127 = 'Metadata key length should be less than 250 characters';
    const CODE_1128 = 'Metadata value length should be less than 1000 characters';
    const CODE_1129 = 'Customer id or Customer information are required';
    const CODE_1130 = 'Customer first name is required';
    const CODE_1131 = 'Customer first name length should be less than 150 characters';
    const CODE_1132 = 'Customer last name is required';
    const CODE_1133 = 'Customer last name length should be less than 150 characters';
    const CODE_1134 = 'Customer middle name length should be less than 150 characters';
    const CODE_1135 = 'Phone number is required';
    const CODE_1136 = 'Phone number country code is invalid';
    const CODE_1137 = 'Phone number is invalid';
    const CODE_1138 = 'Email Address is invalid';
    const CODE_1139 = 'Customer phone number or email address is required';
    const CODE_1140 = 'Card number is invalid';
    const CODE_1141 = 'Card expiry is invalid';
    const CODE_1142 = 'Charge id is missing';
    const CODE_1143 = 'Charge id is invalid';
    const CODE_1144 = 'Charge id not found';
    const CODE_1145 = 'Authenticate type is missing';
    const CODE_1146 = 'Authenticate type is invalid';
    const CODE_1147 = 'Confirmation code is missing';
    const CODE_1148 = 'Confirmation code is invalid';
    const CODE_1149 = 'Currency code is not matching with existing currency code';
    const CODE_1150 = 'Capture amount exceeds with outstanding authorized amount';
    const CODE_1151 = 'Gateway timed out';
    const CODE_1152 = 'Invalid authorize auto schedule type';
    const CODE_1153 = 'Invalid authorize auto schedule time';
    const CODE_1154 = 'BIN is missing';
    const CODE_1155 = 'BIN is invalid';
    const CODE_1156 = 'Refund reason is missing';
    const CODE_1157 = 'Refund reason length should be less than 250 characters';
    const CODE_1158 = 'Refund id is missing';
    const CODE_1159 = 'Refund id is invalid';
    const CODE_1160 = 'Refund not found';
    const CODE_1161 = 'Requested refund amount exceeds';
    const CODE_1162 = 'Invalid row count, should be less than or equal to 50';
    const CODE_1163 = 'Provided card not supported, Please try with another card';
    const CODE_1164 = 'Merchant id is invalid';
    const CODE_1165 = 'Transfer id is missing';
    const CODE_1166 = 'Transfer id is invalid';
    const CODE_1167 = 'Transfer not found';
    const CODE_1168 = 'Requested transfer amount exceeds with charge amount';
    const CODE_1169 = 'Destination and Application cannot be applied in the same charge request';
    const CODE_1170 = 'Transfer Currency code is invalid';
    const CODE_1171 = 'Destination id cannot be duplicated';
    const CODE_1172 = 'Destination id invalid';
    const CODE_2100 = 'Invalid json request';
    const CODE_2101 = 'The server is currently unavailable (overloaded or down)';
    const CODE_2102 = 'Request not found';
    const CODE_2103 = 'Application Required';
    const CODE_2104 = 'Invalid API Key';
    const CODE_2105 = 'API credentials are required';
    const CODE_2106 = 'Please use secret key, public key given';
    const CODE_2107 = 'Authorization Required';
    const CODE_2108 = 'It is likely that you need to grant permission_name';
    const CODE_9998 = 'Required inputs are invalid. Please check the currency or amount...';
    const CODE_9999 = 'Internal server error';

    /**
     * Refreshes this object using the provided values.
     *
     * @param array $values
     * @param null|string|array|Util\RequestOptions $opts
     * @param boolean $partial Defaults to false.
     */
    public function refreshFrom($values, $opts, $partial = false)
    {
        // Unlike most other API resources, the API will omit attributes in
        // error objects when they have a null value. We manually set default
        // values here to facilitate generic error handling.
        $values = array_merge([
            'charge' => null,
            'code' => null,
            'decline_code' => null,
            'doc_url' => null,
            'message' => null,
            'param' => null,
            'payment_intent' => null,
            'payment_method' => null,
            'setup_intent' => null,
            'source' => null,
            'type' => null,
        ], $values);
        parent::refreshFrom($values, $opts, $partial);
    }
}

<?php


namespace Mastercard\Enums;


abstract class MastercardApiOperations
{
    // Sessions
    const CREATE_CHECKOUT_SESSION = 'CREATE_CHECKOUT_SESSION';

    // 3DS
    const CHECK_3DS_ENROLLMENT = 'CHECK_3DS_ENROLLMENT';
    const PROCESS_ACS_RESULT = 'PROCESS_ACS_RESULT';

    // Transactions
    const PAY = 'PAY';
    const VOID = 'VOID';
    const REFUND = 'REFUND';
    const VERIFY = 'VERIFY';
}

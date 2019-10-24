<?php


namespace Mastercard\Enums;


abstract class SessionInteractionOperations
{
    // interaction operations
    const NONE = "NONE";
    const AUTHORIZE = "AUTHORIZE";
    const PURCHASE = "PURCHASE";
    const VERIFY = "VERIFY";
}

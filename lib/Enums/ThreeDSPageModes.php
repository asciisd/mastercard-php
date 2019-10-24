<?php


namespace Mastercard\Enums;


use SebastianBergmann\ObjectEnumerator\Enumerator;

abstract class ThreeDSPageModes extends Enumerator
{
    const CUSTOMIZED = 'CUSTOMIZED';
    const SIMPLE = 'SIMPLE';
}

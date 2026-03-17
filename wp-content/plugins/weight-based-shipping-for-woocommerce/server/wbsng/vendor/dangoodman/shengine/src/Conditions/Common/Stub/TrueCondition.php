<?php
namespace WbsngVendors\Dgm\Shengine\Conditions\Common\Stub;

use WbsngVendors\Dgm\ClassNameAware\ClassNameAware;
use WbsngVendors\Dgm\Shengine\Interfaces\ICondition;


class TrueCondition extends ClassNameAware implements ICondition
{
    public function isSatisfiedBy($value)
    {
        return true;
    }
}
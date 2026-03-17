<?php
namespace WbsngVendors\Dgm\Shengine\Conditions\Common\Enum;

use WbsngVendors\Dgm\ClassNameAware\ClassNameAware;
use WbsngVendors\Dgm\Shengine\Interfaces\ICondition;


class EmptyEnumCondition extends ClassNameAware implements ICondition
{
    public function isSatisfiedBy($value)
    {
        return empty($value);
    }
}
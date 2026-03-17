<?php
namespace WbsngVendors\Dgm\Shengine\Conditions\Common\Compare;


class GreaterOrEqualCondition extends CompareCondition
{
    public function isSatisfiedBy($value)
    {
        return $this->comparator->greater($value, $this->compareWith, true);
    }
}
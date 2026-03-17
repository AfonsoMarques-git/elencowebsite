<?php
namespace WbsngVendors\Dgm\Shengine\Conditions\Common\Compare;


class NotEqualCondition extends CompareCondition
{
    public function isSatisfiedBy($value)
    {
        return !$this->comparator->equal($value, $this->compareWith);
    }
}
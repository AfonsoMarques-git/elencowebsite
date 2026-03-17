<?php
namespace WbsngVendors\Dgm\Shengine\Conditions\Common;

use WbsngVendors\Dgm\Shengine\Interfaces\ICondition;


class AggregateCondition extends AbstractCondition
{
    public function isSatisfiedBy($value)
    {
        return $this->condition->isSatisfiedBy($value);
    }

    /** @var ICondition */
    protected $condition;
}
<?php
namespace WbsngVendors\Dgm\Shengine\Conditions\Common\Compare;

use WbsngVendors\Dgm\Comparator\IComparator;
use WbsngVendors\Dgm\Range\Range;
use WbsngVendors\Dgm\Shengine\Conditions\Common\AbstractCondition;


class BetweenCondition extends AbstractCondition
{
    public function __construct(Range $range, IComparator $comparator)
    {
        $this->range = $range;
        $this->comparator = $comparator;
    }

    public function isSatisfiedBy($value)
    {
        return $this->range->includes($value, $this->comparator);
    }

    private $range;
    private $comparator;
}
<?php
namespace WbsngVendors\Dgm\Shengine\Conditions\Common\Compare;

use WbsngVendors\Dgm\Comparator\IComparator;
use WbsngVendors\Dgm\Shengine\Conditions\Common\AbstractCondition;


abstract class CompareCondition extends AbstractCondition
{
    public function __construct($compareWith, IComparator $comparator)
    {
        $this->compareWith = $compareWith;
        $this->comparator = $comparator;
    }


    protected $compareWith;
    protected $comparator;
}
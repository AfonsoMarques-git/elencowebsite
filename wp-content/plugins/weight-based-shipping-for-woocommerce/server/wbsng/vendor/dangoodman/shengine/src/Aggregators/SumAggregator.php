<?php
namespace WbsngVendors\Dgm\Shengine\Aggregators;

use WbsngVendors\Dgm\Shengine\Interfaces\IRate;
use WbsngVendors\Dgm\Shengine\Processing\RateRegister;


class SumAggregator extends ReduceAggregator
{
    protected function reduce(IRate $carry = null, IRate $current)
    {
        if (!isset($carry)) {
            $carry = new RateRegister();
        }

        $carry->add($current);

        return $carry;
    }
}
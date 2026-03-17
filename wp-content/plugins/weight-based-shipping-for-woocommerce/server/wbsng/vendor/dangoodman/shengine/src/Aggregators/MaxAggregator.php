<?php
namespace WbsngVendors\Dgm\Shengine\Aggregators;

use WbsngVendors\Dgm\Shengine\Interfaces\IRate;


class MaxAggregator extends ReduceAggregator
{
    protected function reduce(IRate $carry = null, IRate $current)
    {
        if (!isset($carry) || $carry->getCost() < $current->getCost()) {
            $carry = $current;
        }

        return $carry;
    }
}
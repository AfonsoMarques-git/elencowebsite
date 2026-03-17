<?php
namespace WbsngVendors\Dgm\Shengine\Aggregators;


use WbsngVendors\Dgm\ClassNameAware\ClassNameAware;
use WbsngVendors\Dgm\Shengine\Interfaces\IAggregator;
use WbsngVendors\Dgm\Shengine\Interfaces\IRate;
use Dgm\Shengine\Model\Rate;
use WbsngVendors\Dgm\Shengine\Processing\RateRegister;


abstract class ReduceAggregator extends ClassNameAware implements IAggregator
{
    public function aggregateRates(array $rates)
    {
        $rate = $this->_reduce($rates);

        if ($rate instanceof RateRegister) {
            $rate = $rate->toRate();
        }

        return $rate;
    }

    /**
     * @param IRate $carry
     * @param IRate $current
     * @return IRate
     */
    protected abstract function reduce(IRate $carry = null, IRate $current);

    private function _reduce(array $rates)
    {
        $carry = null;
        foreach ($rates as $rate) {
            $carry = $this->reduce($carry, $rate);
        }

        return $carry;
    }
}
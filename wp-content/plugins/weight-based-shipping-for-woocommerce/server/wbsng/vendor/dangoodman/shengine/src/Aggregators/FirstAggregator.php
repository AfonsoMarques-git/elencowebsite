<?php
namespace WbsngVendors\Dgm\Shengine\Aggregators;

use WbsngVendors\Dgm\ClassNameAware\ClassNameAware;
use WbsngVendors\Dgm\Shengine\Interfaces\IAggregator;


class FirstAggregator extends ClassNameAware implements IAggregator
{
    public function aggregateRates(array $rates)
    {
        return $rates ? reset($rates) : null;
    }
}
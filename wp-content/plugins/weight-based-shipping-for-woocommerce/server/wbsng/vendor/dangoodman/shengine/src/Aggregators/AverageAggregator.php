<?php
namespace WbsngVendors\Dgm\Shengine\Aggregators;

use WbsngVendors\Dgm\ClassNameAware\ClassNameAware;
use WbsngVendors\Dgm\Shengine\Interfaces\IAggregator;
use WbsngVendors\Dgm\Shengine\Model\Rate;


class AverageAggregator extends ClassNameAware implements IAggregator
{
    public function __construct()
    {
        $this->sum = new SumAggregator();
    }

    public function aggregateRates(array $rates)
    {
        $result = $this->sum->aggregateRates($rates);
        if (isset($result)) {
            $result = new Rate($result->getCost() / count($rates), $result->getTitle());
        }

        return $result;
    }

    private $sum;
}
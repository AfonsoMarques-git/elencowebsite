<?php
namespace WbsngVendors\Dgm\Shengine\Calculators;

use WbsngVendors\Dgm\Arrays\Arrays;
use WbsngVendors\Dgm\Range\Range;
use WbsngVendors\Dgm\Shengine\Interfaces\ICalculator;
use WbsngVendors\Dgm\Shengine\Interfaces\IPackage;
use WbsngVendors\Dgm\Shengine\Interfaces\IRate;
use WbsngVendors\Dgm\Shengine\Model\Rate;


class ClampCalculator implements ICalculator
{
    public function __construct(ICalculator $calculator, Range $range)
    {
        $this->range = $range;
        $this->calculator = $calculator;
    }

    public function calculateRatesFor(IPackage $package)
    {
        $range = $this->range;
        return Arrays::map($this->calculator->calculateRatesFor($package), function(IRate $rate) use($range) {
            return new Rate($range->clamp($rate->getCost()), $rate->getTitle());
        });
    }

    public function multipleRatesExpected()
    {
        return $this->calculator->multipleRatesExpected();
    }

    private $calculator;
    private $range;
}
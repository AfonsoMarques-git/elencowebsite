<?php
namespace WbsngVendors\Dgm\Shengine\Calculators;

use WbsngVendors\Dgm\Shengine\Interfaces\ICalculator;
use WbsngVendors\Dgm\Shengine\Interfaces\IPackage;
use WbsngVendors\Dgm\Shengine\Model\Rate;


class ConstantCalculator implements ICalculator
{
    public function __construct($cost)
    {
        $this->cost = $cost;
    }

    public function calculateRatesFor(IPackage $package)
    {
        return array(new Rate($this->cost));
    }

    public function multipleRatesExpected()
    {
        return false;
    }

    private $cost;
}

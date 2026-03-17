<?php
namespace WbsngVendors\Dgm\Shengine\Calculators;

use WbsngVendors\Dgm\Shengine\Interfaces\ICalculator;
use WbsngVendors\Dgm\Shengine\Interfaces\IPackage;
use WbsngVendors\Dgm\Shengine\Model\Rate;


class FreeCalculator implements ICalculator
{
    public function calculateRatesFor(IPackage $package)
    {
        return array(new Rate(0));
    }

    public function multipleRatesExpected()
    {
        return false;
    }
}
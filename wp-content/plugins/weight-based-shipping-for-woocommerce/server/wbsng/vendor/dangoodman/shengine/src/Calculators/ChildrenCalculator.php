<?php
namespace WbsngVendors\Dgm\Shengine\Calculators;

use WbsngVendors\Dgm\Shengine\Interfaces\ICalculator;
use WbsngVendors\Dgm\Shengine\Interfaces\IPackage;
use WbsngVendors\Dgm\Shengine\Interfaces\IProcessor;


class ChildrenCalculator implements ICalculator
{
    public function __construct(IProcessor $processor, $children)
    {
        $this->processor = $processor;
        $this->children = $children;
    }

    public function calculateRatesFor(IPackage $package)
    {
        return $this->processor->process($this->children, $package);
    }

    public function multipleRatesExpected()
    {
        return !empty($this->children);
    }

    private $processor;
    private $children;
}
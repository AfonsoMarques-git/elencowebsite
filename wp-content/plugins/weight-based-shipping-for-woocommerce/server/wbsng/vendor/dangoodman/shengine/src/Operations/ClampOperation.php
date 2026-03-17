<?php
namespace WbsngVendors\Dgm\Shengine\Operations;

use WbsngVendors\Dgm\Range\Range;
use WbsngVendors\Dgm\Shengine\Interfaces\IPackage;
use WbsngVendors\Dgm\Shengine\Processing\Registers;


class ClampOperation extends AbstractOperation
{
    public function __construct(Range $range)
    {
        $this->range = $range;
    }

    public function process(Registers $registers, IPackage $package)
    {
        foreach ($registers->rates as $rate) {
            $rate->cost = $this->range->clamp($rate->cost);
        }
    }

    public function getType()
    {
        return self::MODIFIER;
    }

    private $range;
}
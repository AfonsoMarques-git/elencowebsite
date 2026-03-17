<?php
namespace WbsngVendors\Dgm\Shengine\Operations;

use InvalidArgumentException;
use WbsngVendors\Dgm\Shengine\Interfaces\IPackage;
use WbsngVendors\Dgm\Shengine\Processing\Registers;


class MultiplyOperation extends AbstractOperation
{
    public function __construct($multiplier)
    {
        if (!is_numeric($multiplier)) {
            throw new InvalidArgumentException();
        }

        $this->multiplier = $multiplier;
    }

    public function process(Registers $registers, IPackage $package)
    {
        foreach ($registers->rates as $rate) {
            $rate->cost *= $this->multiplier;
        }
    }

    public function getType()
    {
        return self::MODIFIER;
    }

    private $multiplier;
}
<?php
namespace WbsngVendors\Dgm\Shengine\Attributes;

use WbsngVendors\Dgm\Shengine\Interfaces\IPackage;
use WbsngVendors\Dgm\Shengine\Model\Price;


class PriceAttribute extends AbstractAttribute
{
    public function __construct($flags = Price::BASE)
    {
        $this->flags = $flags;
    }

    public function getValue(IPackage $package)
    {
        return $package->getPrice($this->flags);
    }

    private $flags;
}
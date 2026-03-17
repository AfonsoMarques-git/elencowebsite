<?php
namespace WbsngVendors\Dgm\Shengine\Attributes;

use WbsngVendors\Dgm\Shengine\Interfaces\IPackage;


class DestinationAttribute extends AbstractAttribute
{
    public function getValue(IPackage $package)
    {
        return $package->getDestination();
    }
}
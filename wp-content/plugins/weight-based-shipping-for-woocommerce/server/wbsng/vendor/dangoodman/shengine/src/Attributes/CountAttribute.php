<?php
namespace WbsngVendors\Dgm\Shengine\Attributes;

use WbsngVendors\Dgm\Shengine\Interfaces\IPackage;


class CountAttribute extends AbstractAttribute
{
    public function getValue(IPackage $package)
    {
        return count($package->getItems());
    }
}
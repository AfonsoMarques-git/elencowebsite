<?php
namespace WbsngVendors\Dgm\Shengine\Attributes;

use WbsngVendors\Dgm\Shengine\Interfaces\IPackage;


abstract class SumAttribute extends MapAttribute
{
    public function getValue(IPackage $package)
    {
        return array_sum(parent::getValue($package));
    }
}
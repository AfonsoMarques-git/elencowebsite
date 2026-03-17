<?php
namespace WbsngVendors\Dgm\Shengine\Conditions\Package;

use WbsngVendors\Dgm\Shengine\Conditions\Common\AbstractCondition;
use WbsngVendors\Dgm\Shengine\Interfaces\IPackage;


abstract class AbstractPackageCondition extends AbstractCondition
{
    public function isSatisfiedBy($package)
    {
        return $this->isSatisfiedByPackage($package);
    }

    abstract protected function isSatisfiedByPackage(IPackage $package);
}
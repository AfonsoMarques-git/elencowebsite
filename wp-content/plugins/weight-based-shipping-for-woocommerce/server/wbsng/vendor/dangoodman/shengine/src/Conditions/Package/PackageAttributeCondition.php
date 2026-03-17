<?php
namespace WbsngVendors\Dgm\Shengine\Conditions\Package;

use WbsngVendors\Dgm\Shengine\Interfaces\IAttribute;
use WbsngVendors\Dgm\Shengine\Interfaces\ICondition;
use WbsngVendors\Dgm\Shengine\Interfaces\IPackage;


class PackageAttributeCondition extends AbstractPackageCondition
{
    public function __construct(ICondition $condition, IAttribute $attribute)
    {
        $this->condition = $condition;
        $this->attribute = $attribute;
    }

    public function isSatisfiedByPackage(IPackage $package)
    {
        return $this->condition->isSatisfiedBy($this->attribute->getValue($package));
    }

    private $condition;
    private $attribute;
}
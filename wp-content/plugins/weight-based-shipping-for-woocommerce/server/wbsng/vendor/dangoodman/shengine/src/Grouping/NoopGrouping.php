<?php
namespace WbsngVendors\Dgm\Shengine\Grouping;

use WbsngVendors\Dgm\Shengine\Interfaces\IGrouping;
use WbsngVendors\Dgm\Shengine\Interfaces\IItem;
use Dgm\Shengine\Interfaces\IPackage;


class NoopGrouping implements IGrouping
{
    public function getPackageIds(IItem $item)
    {
        return ['noop'];
    }

    public function multiplePackagesExpected()
    {
        return false;
    }
}

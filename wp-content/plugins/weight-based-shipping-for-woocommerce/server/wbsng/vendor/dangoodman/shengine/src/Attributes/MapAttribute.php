<?php
namespace WbsngVendors\Dgm\Shengine\Attributes;

use WbsngVendors\Dgm\Shengine\Interfaces\IItem;
use WbsngVendors\Dgm\Shengine\Interfaces\IPackage;


abstract class MapAttribute extends AbstractAttribute
{
    public function getValue(IPackage $package)
    {
        $result = array();

        foreach ($package->getItems() as $key => $item) {
            $result[$key] = $this->getItemValue($item);
        }

        return $result;
    }

    protected abstract function getItemValue(IItem $item);
}
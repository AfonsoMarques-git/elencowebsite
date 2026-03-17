<?php
namespace WbsngVendors\Dgm\Shengine\Attributes;

use WbsngVendors\Dgm\Shengine\Interfaces\IItem;


class ItemAttribute extends MapAttribute
{
    protected function getItemValue(IItem $item)
    {
        return spl_object_hash($item);
    }
}
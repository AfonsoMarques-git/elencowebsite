<?php
namespace WbsngVendors\Dgm\Shengine\Attributes;

use WbsngVendors\Dgm\Shengine\Interfaces\IItem;


class ItemDimensionsAttribute extends MapAttribute
{
    protected function getItemValue(IItem $item)
    {
        $dimensions = $item->getDimensions();
        $box = array($dimensions->length, $dimensions->width, $dimensions->height);
        return $box;
    }
}
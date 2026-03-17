<?php
namespace WbsngVendors\Dgm\Shengine\Attributes;

use WbsngVendors\Dgm\Shengine\Interfaces\IItem;


class ProductAttribute extends MapAttribute
{
    protected function getItemValue(IItem $item)
    {
        return $item->getProductId();
    }
}
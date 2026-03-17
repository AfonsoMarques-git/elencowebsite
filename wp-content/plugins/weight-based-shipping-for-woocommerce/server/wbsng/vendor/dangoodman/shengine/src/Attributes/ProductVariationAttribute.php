<?php
namespace WbsngVendors\Dgm\Shengine\Attributes;

use WbsngVendors\Dgm\Shengine\Interfaces\IItem;


class ProductVariationAttribute extends MapAttribute
{
    protected function getItemValue(IItem $item)
    {
        $id = $item->getProductVariationId();
        $id = isset($id) ? $id : $item->getProductId();
        return $id;
    }
}
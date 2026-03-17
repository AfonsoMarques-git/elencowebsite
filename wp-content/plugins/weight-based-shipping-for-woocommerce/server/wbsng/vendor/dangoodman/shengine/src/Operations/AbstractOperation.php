<?php
namespace WbsngVendors\Dgm\Shengine\Operations;

use WbsngVendors\Dgm\ClassNameAware\ClassNameAware;
use WbsngVendors\Dgm\Shengine\Interfaces\IOperation;


abstract class AbstractOperation extends ClassNameAware implements IOperation
{
    public function getType()
    {
        return self::OTHER;
    }

    public function canOperateOnMultipleRates()
    {
        return true;
    }
}
<?php
namespace WbsngVendors\Dgm\Shengine\Interfaces;


interface IAttribute
{
    /**
     * @param IPackage $package
     * @return mixed
     */
    function getValue(IPackage $package);
}
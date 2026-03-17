<?php
namespace WbsngVendors\Dgm\Shengine;

use WbsngVendors\Dgm\SimpleProperties\SimpleProperties;
use WbsngVendors\Dgm\Shengine\Interfaces\IGrouping;


/**
 * @property-read bool $capture
 * @property-read IGrouping $grouping
 * @property-read bool $requireAllPackages
 */
class RuleMatcherMeta extends SimpleProperties
{
    public function __construct($capture, IGrouping $grouping, $requireAllPackages = false)
    {
        $this->capture = $capture;
        $this->grouping = $grouping;
        $this->requireAllPackages = $requireAllPackages;
    }


    protected $capture;
    protected $grouping;
    protected $requireAllPackages;
}
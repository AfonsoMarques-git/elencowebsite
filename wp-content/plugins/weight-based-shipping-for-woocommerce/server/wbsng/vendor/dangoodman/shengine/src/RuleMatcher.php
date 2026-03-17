<?php
namespace WbsngVendors\Dgm\Shengine;

use WbsngVendors\Dgm\Shengine\Interfaces\ICondition;
use WbsngVendors\Dgm\Shengine\Interfaces\IMatcher;
use WbsngVendors\Dgm\Shengine\Interfaces\IPackage;


class RuleMatcher implements IMatcher
{
    public function __construct(RuleMatcherMeta $meta, ICondition $condition)
    {
        $this->meta = $meta;
        $this->condition = $condition;
    }

    public function getMatchingPackage(IPackage $package)
    {
        return $package->splitFilterMerge($this->meta->grouping, $this->condition, $this->meta->requireAllPackages);
    }

    public function isCapturingMatcher()
    {
        return $this->meta->capture;
    }

    private $meta;
    private $condition;
}

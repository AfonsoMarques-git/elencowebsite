<?php
namespace WbsngVendors\Dgm\Shengine\Conditions\Common;

use WbsngVendors\Dgm\Shengine\Interfaces\ICondition;


abstract class GroupCondition extends AbstractCondition
{
    public function __construct(array $conditions)
    {
        $this->conditions = $conditions;
    }

    /** @var ICondition[] */
    protected $conditions;
}
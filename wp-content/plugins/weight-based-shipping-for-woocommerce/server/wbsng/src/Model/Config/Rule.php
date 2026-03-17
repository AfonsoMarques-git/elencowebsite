<?php declare(strict_types=1);
namespace Aikinomi\Wbsng\Model\Config;

use Aikinomi\Wbsng\Common\Decimal;
use Aikinomi\Wbsng\Mapping\Context;
use Aikinomi\Wbsng\Mapping\Exceptions\Invalid;
use Aikinomi\Wbsng\Mapping\T;
use Aikinomi\Wbsng\Model\Config\Method\PriceSettings;
use Aikinomi\Wbsng\Model\Order\Bundle;
use Aikinomi\Wbsng\Multicurrency;
use WbsngVendors\Dgm\Shengine\Model\Destination;


/**
 * @property-read string $name
 * @property-read DestCond $locations
 * @property-read ShclassCond $shclasses
 * @property-read Range $weight
 * @property-read Range $price
 * @property-read Charge $charge
 * @property-read ?Action $action
 *
 * @immutable
 */
class Rule
{
    use RuleMapping;


    /**
     * @return ?Result null means deny
     */
    public function rate(Bundle $items, ?Destination $dest, PriceSettings $priceSettings): ?Result
    {
        $matched = $this->match($items, $dest, $priceSettings);
        if ($matched->empty()) {
            // RequireAction is the only action that takes place on non-matching rules.
            // All other actions are supposed to modify matching rules.
            if ($this->action instanceof RequireAction) {
                return null;
            }
            return Result::empty($items);
        }

        $action = $this->action;
        switch (true) {
            case $action instanceof RequireAction: // no-op for matching rules
            case $action instanceof PassAction:
                return new Result(
                    $this->charge->calc($matched),
                    $matched,
                    Bundle::$EMPTY
                );
            case $action instanceof StopAction:
                return new Result(
                    $this->charge->calc($matched),
                    $matched,
                    $items,
                );
            case $action instanceof DropAction:
                $drop = $matched; // optimization
                if ($action->drop !== $this->shclasses) {
                    $drop = $action->drop->match($items);
                }
                return new Result(
                    $this->charge->calc($matched),
                    $matched,
                    $drop,
                );
            case $action instanceof DenyAction:
                return null;
            default:
                throw new \LogicException("Unknown action type: " . get_class($action));
        }
    }

    private function match(Bundle $items, ?Destination $dest, PriceSettings $priceSettings): Bundle
    {
        $empty = Bundle::$EMPTY;

        if (!$this->locations->match($dest)) {
            return $empty;
        }

        $matched = $this->shclasses->match($items);
        if ($matched->empty()) {
            return $empty;
        }

        if (!$this->weight->includes($matched->weight())) {
            return $empty;
        }

        $price = $matched->price()->get($priceSettings->afterTaxes, $priceSettings->afterDiscounts);
        if (!$this->price->includes($price)) {
            return $empty;
        }

        return $matched;
    }
}


class Result
{
    /**
     * @var Decimal
     */
    public $price;

    /**
     * @var Bundle
     */
    public $matched;

    /**
     * @var Bundle
     */
    public $dropped;

    public static function empty(Bundle $dropped): self
    {
        return new self(Decimal::$zero, Bundle::$EMPTY, $dropped);
    }

    public function __construct(Decimal $price, Bundle $matched, Bundle $dropped)
    {
        $this->price = $price;
        $this->matched = $matched;
        $this->dropped = $dropped;
    }
}


abstract class Action {
}

class PassAction extends Action {
}

class StopAction extends Action {
}

class DropAction extends Action {
    public $drop;
    public function __construct(ShclassCond $drop) {
        $this->drop = $drop;
    }
}

class DenyAction extends Action {
}

class RequireAction extends Action {
}


trait RuleMapping
{
    /**
     * @return static|null
     */
    public static function unserialize(array $data): ?self
    {
        $data = Context::of($data);

        $disabled = $data['disabled']->map([T::class, 'optionalBool'], false);
        if ($disabled) {
            return null;
        }

        $rule = new static();
        $rule->ctx = $data;
        return $rule;
    }

    /**
     * @throws Invalid
     */
    public function __get(string $prop)
    {
        if (!isset($this->props[$prop])) {
            $this->props[$prop] = $this->init($prop);
        }

        return $this->props[$prop];
    }

    /**
     * @var Context
     */
    private $ctx;

    private $props = [];

    /**
     * @throws Invalid If the config processing failed
     * @noinspection PhpDocRedundantThrowsInspection
     */
    private function init(string $prop)
    {
        switch ($prop) {

            case 'name':
                return $this->ctx[$prop]->map([T::class, 'optionalString'], '');

            case 'locations':
                return $this->ctx[$prop]->map([DestCond::class, 'unserialize']);

            case 'shclasses':
                return $this->ctx[$prop]->map([ShclassCond::class, 'unserialize']);

            case 'weight':
                return $this->ctx[$prop]->map(function($v) {
                    return Range::unserialize($v);
                });

            case 'price':
                return $this->ctx[$prop]->map(function($v) {
                    $convert = $this->ctx->convert() ? [Multicurrency::class, 'convert'] : null;
                    return Range::unserialize($v, $convert);
                });

            case 'charge':
                return $this->ctx[$prop]->map([Charge::class, 'unserialize']);

            case 'action':
                return $this->ctx[$prop]->map(function($x) {
                    return $this->mapAction($x);
                });

            default:
                throw new \LogicException("unknown field $prop");
        }
    }

    private function mapAction(?array $action): ?Action
    {
        if ($action === null) {
            return new PassAction();
        }

        $action = Context::of($action);

        return $action['type']->map(function(string $v) use($action) {
            switch ($v) {

                case 'drop':
                    $shclasses = $action['items']->map(function($x) {
                        return $x === null
                            ? $this->shclasses
                            : Context::of($x)->map([ShclassCond::class, 'unserialize']);
                    });
                    return new DropAction($shclasses);

                case 'finish':
                    return new StopAction();

                case 'cancel':
                    return new DenyAction();

                case 'require':
                    return new RequireAction();

                default:
                    throw new Invalid('unsupported action type');
            }
        });
    }
}
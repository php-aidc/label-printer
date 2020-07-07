<?php

/**
 * This file is part of PhpAidc LabelPrinter package.
 *
 * © Appwilio (https://appwilio.com)
 * © JhaoDa (https://github.com/jhaoda)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace PhpAidc\LabelPrinter\Label;

use PhpAidc\LabelPrinter\Contract\Condition;
use PhpAidc\LabelPrinter\Contract\Label as LabelContract;

final class BooleanCondition implements Condition
{
    /** @var mixed */
    private $value;

    /** @var callable */
    private $callback;

    public function __construct($value, callable $callback)
    {
        $this->value = $value;
        $this->callback = $callback;
    }

    public function isTruthy(): bool
    {
        return (bool) $this->value;
    }

    public function apply(LabelContract $label): LabelContract
    {
        \call_user_func($this->callback, $label, $this->value);

        return $label;
    }
}

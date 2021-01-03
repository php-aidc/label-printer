<?php

/**
 * This file is part of PhpAidc LabelPrinter package.
 *
 * © Appwilio (https://appwilio.com)
 * © JhaoDa (https://github.com/jhaoda)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace PhpAidc\LabelPrinter\Label;

use PhpAidc\LabelPrinter\Contract\Condition;
use PhpAidc\LabelPrinter\Contract\Label as LabelContract;

final class LanguageCondition implements Condition
{
    /** @var string */
    private $language;

    /** @var callable */
    private $callback;

    public function __construct(string $language, callable $callback)
    {
        $this->language = $language;
        $this->callback = $callback;
    }

    public function isMatch(string $language): bool
    {
        return $this->language === $language;
    }

    public function apply(LabelContract $label): LabelContract
    {
        \call_user_func($this->callback, $label);

        return $label;
    }
}

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

final class Condition
{
    /** @var string */
    private $language;

    /** @var \Closure */
    private $closure;

    public function __construct(string $language, \Closure $closure)
    {
        $this->language = $language;
        $this->closure = $closure;
    }

    public function isMatch(string $language): bool
    {
        return $this->language === $language;
    }

    public function call(\PhpAidc\LabelPrinter\Contract\Label $label): iterable
    {
        \call_user_func($this->closure, $label);

        return $label->getCommands($this->language);
    }
}

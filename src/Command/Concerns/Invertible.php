<?php

/**
 * This file is part of PhpAidc LabelPrinter package.
 *
 *  © Appwilio (https://appwilio.com)
 *  © JhaoDa (https://github.com/jhaoda)
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

declare(strict_types=1);

namespace PhpAidc\LabelPrinter\Command\Concerns;

trait Invertible
{
    /** @var bool */
    private $invert;

    public function invert(bool $value = true)
    {
        $this->invert = $value;

        return $this;
    }

    public function isInverted(): bool
    {
        return (bool) $this->invert;
    }
}

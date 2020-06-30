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

namespace PhpAidc\LabelPrinter\Command\Concerns;

trait FontAware
{
    /** @var string */
    private $fontName;

    /** @var float|int|string|null */
    private $fontSize;

    public function getFontName(): string
    {
        return $this->fontName;
    }

    public function getFontSize()
    {
        return $this->fontSize;
    }
}

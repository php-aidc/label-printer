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

trait ImageFallback
{
    /** @var bool */
    private $emulate = false;

    public function emulate()
    {
        $this->emulate = true;

        return $this;
    }

    public function shouldEmulate(): bool
    {
        return $this->emulate;
    }
}

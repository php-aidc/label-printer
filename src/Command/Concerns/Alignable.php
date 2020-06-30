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

use PhpAidc\LabelPrinter\Enum\Anchor;

trait Alignable
{
    /** @var Anchor|null */
    private $anchor;

    public function anchor(Anchor $anchor)
    {
        $this->anchor = $anchor;

        return $this;
    }

    public function getAnchor(): ?Anchor
    {
        return $this->anchor;
    }
}

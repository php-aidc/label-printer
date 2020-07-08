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

namespace PhpAidc\LabelPrinter\Contract;

use PhpAidc\LabelPrinter\Enum\Charset;

interface Label extends Job, \IteratorAggregate
{
    public function getCopies(): int;

    public function getCharset(): ?Charset;

    public function getMedia(): Media;
}

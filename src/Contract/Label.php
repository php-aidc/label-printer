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
use PhpAidc\LabelPrinter\Enum\Direction;

interface Label extends Job
{
    public function charset(Charset $value);

    public function copies(int $copies);

    public function direction(Direction $value);

    public function add(Command $command);

    public function getMedia(): array;

    public function getCharset(): ?Charset;

    public function getCopies(): int;

    public function getCommands(string $language): iterable;
}

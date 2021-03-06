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

interface Language
{
    public function isSupport(Command $command): bool;

    public function compileDeclaration(Label $label): iterable;

    public function compileCommand(Command $command): iterable;

    public function compilePrint(int $copies): iterable;
}

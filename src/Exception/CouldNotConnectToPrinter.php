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

namespace PhpAidc\LabelPrinter\Exception;

class CouldNotConnectToPrinter extends \RuntimeException implements LabelPrinterException
{
    public static function becauseCannotOpenFile(string $file): self
    {
        return new self(\sprintf('Cannot open file "%s".', $file));
    }

    public static function becauseNetworkError(string $message): self
    {
        return new self($message);
    }
}

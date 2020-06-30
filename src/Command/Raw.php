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

namespace PhpAidc\LabelPrinter\Command;

use PhpAidc\LabelPrinter\Contract\Command;

/**
 * Sends raw command to the printer.
 */
final class Raw implements Command
{
    /** @var iterable */
    private $data;

    /**
     * @param iterable|string $data
     */
    public function __construct($data)
    {
        if (\is_string($data)) {
            $this->data = [$data];
        } else {
            $this->data = $data;
        }
    }

    public function getData(): iterable
    {
        return $this->data;
    }
}

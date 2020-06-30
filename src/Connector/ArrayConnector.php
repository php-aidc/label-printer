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

namespace PhpAidc\LabelPrinter\Connector;

use PhpAidc\LabelPrinter\Contract\Connector;

final class ArrayConnector implements Connector
{
    /** @var array|null */
    private $buffer;

    public function open(): void
    {
        if (\is_array($this->buffer)) {
            return;
        }

        $this->buffer = [];
    }

    public function close(): void
    {
        $this->buffer = null;
    }

    public function write($data): int
    {
        $this->open();

        $this->buffer[] = $data;

        return \strlen($data);
    }

    public function read(int $length = 0)
    {
        throw new \BadMethodCallException('Not applicable.');
    }

    public function get(): array
    {
        return $this->buffer;
    }

    public function __destruct()
    {
        $this->close();
    }
}

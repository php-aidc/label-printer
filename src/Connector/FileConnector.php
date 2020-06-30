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

namespace PhpAidc\LabelPrinter\Connector;

use PhpAidc\LabelPrinter\Contract\Connector;
use PhpAidc\LabelPrinter\Exception\CouldNotConnectToPrinter;

final class FileConnector implements Connector
{
    /** @var string */
    private $file;

    /** @var resource */
    private $handle;

    public function __construct(string $file)
    {
        $this->file = $file;
    }

    public function open(): void
    {
        if (\is_resource($this->handle)) {
            return;
        }

        $this->handle = \fopen($this->file, 'w+b');

        if ($this->handle === false) {
            throw CouldNotConnectToPrinter::becauseCannotOpenFile($this->file);
        }
    }

    public function close(): void
    {
        if (\is_resource($this->handle)) {
            \fclose($this->handle);

            $this->handle = null;
        }
    }

    public function write($data): int
    {
        $this->open();

        return \fwrite($this->handle, $data, \strlen($data));
    }

    public function read(int $length = 0)
    {
        $this->open();

        return \fread($this->handle, $length);
    }

    public function __destruct()
    {
        $this->close();
    }
}

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

final class NetworkConnector implements Connector
{
    /** @var string */
    private $host;

    /** @var int */
    private $port;

    /** @var int */
    private $timeout;

    /** @var resource */
    private $handle;

    /** @var array|null */
    private $lastError;

    public function __construct(string $host, int $port = 9100, int $timeout = 1)
    {
        $this->host = $host;
        $this->port = $port;
        $this->timeout = $timeout;
    }

    public function open(): void
    {
        if (\is_resource($this->handle)) {
            return;
        }

        $this->setErrorHandler();

        try {
            $this->handle = \stream_socket_client("tcp://{$this->host}:{$this->port}", $severity, $message, $this->timeout);

            $this->flushErrors();

            \stream_set_timeout($this->handle, $this->timeout, 0);
        } catch (\ErrorException $e) {
            throw CouldNotConnectToPrinter::becauseNetworkError($e->getMessage());
        }
    }

    public function write($data): int
    {
        $this->open();

        $this->setErrorHandler();

        $result = \fwrite($this->handle, $data, \strlen($data));

        $this->flushErrors();

        return $result;
    }

    public function read(int $length = 0)
    {
        $this->open();

        $result = '';

        while (true) {
            $this->setErrorHandler();

            $buffer = \fread($this->handle, 8192);

            $this->flushErrors();

            $result .= $buffer;

            if (\mb_strlen($buffer) < 8192) {
                break;
            }
        }

        return $result;
    }

    public function close(): void
    {
        if (\is_resource($this->handle)) {
            $this->setErrorHandler();

            \fclose($this->handle);

            $this->flushErrors();

            $this->handle = null;
        }
    }

    public function __destruct()
    {
        $this->close();
    }

    private function setErrorHandler(): void
    {
        $this->lastError = null;

        \set_error_handler(function ($severity, $message, $file, $line) {
            $this->lastError = \compact('severity', 'message', 'file', 'line');
        });
    }

    private function flushErrors(): void
    {
        \restore_error_handler();

        if ($this->lastError) {
            throw new \ErrorException(
                $this->lastError['message'],
                0,
                $this->lastError['severity'],
                $this->lastError['file'],
                $this->lastError['line']
            );
        }
    }
}

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

namespace PhpAidc\LabelPrinter;

use PhpAidc\LabelPrinter\Contract\Label;
use PhpAidc\LabelPrinter\Contract\Connector;

final class Printer
{
    /** @var Connector */
    private $connector;

    /** @var Compiler|null */
    private $compiler;

    public function __construct(Connector $connector, ?Compiler $compiler = null)
    {
        $this->connector = $connector;
        $this->compiler = $compiler;
    }

    public function print(Label $label, int $copies = 1): void
    {
        if ($this->compiler === null) {
            throw new \DomainException();
        }

        $this->connector->write($this->compiler->compile($label, $copies));
    }

    public function send($payload): void
    {
        $this->connector->write($payload);
    }

    public function ask(string $message)
    {
        $this->connector->write($message);

        return $this->connector->read();
    }
}

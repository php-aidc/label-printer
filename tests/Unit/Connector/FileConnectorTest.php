<?php

/**
 * This file is part of PhpAidc LabelPrinter package.
 *
 * © Appwilio (https://appwilio.com)
 * © JhaoDa (https://github.com/jhaoda)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace PhpAidc\LabelPrinter\Tests\Unit\Connector;

use PhpAidc\LabelPrinter\Connector\FileConnector;
use PHPUnit\Framework\TestCase;

class FileConnectorTest extends TestCase
{
    public function testWrite(): void
    {
        $connector = new FileConnector($file = \tempnam(\sys_get_temp_dir(), 'alp'));

        $connector->write($data = 'HELLO');

        $this->assertEquals($data, \file_get_contents($file));

        $connector->close();

        \unlink($file);
    }
}

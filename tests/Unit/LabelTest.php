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

namespace PhpAidc\LabelPrinter\Tests\Unit;

use PhpAidc\LabelPrinter\Enum\Charset;
use PhpAidc\LabelPrinter\Enum\Direction;
use PhpAidc\LabelPrinter\Label\Label;
use PHPUnit\Framework\TestCase;

class LabelTest extends TestCase
{
    public function testCannotInstantiateLabelWithoutUnitAndWithSize(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new Label(null, 40, 20);
    }

    public function testProperties(): void
    {
        $label = (new Label())
            ->charset(Charset::UTF8())
            ->direction(Direction::BACKWARD());

        self::assertEquals(Charset::UTF8(), $label->getCharset());
        self::assertEquals(Direction::BACKWARD(), $label->getDirection());
    }
}

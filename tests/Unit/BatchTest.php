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

namespace PhpAidc\LabelPrinter\Tests\Unit;

use PhpAidc\LabelPrinter\Label\Batch;
use PhpAidc\LabelPrinter\Label\Element;
use PhpAidc\LabelPrinter\Label\Label;
use PHPUnit\Framework\TestCase;

class BatchTest extends TestCase
{
    public function testBatch(): void
    {
        $label = (new Label())->add(Element::raw(''));

        $batch = new Batch();

        $batch->add(clone $label);
        $batch->add(clone $label);

        $this->assertEquals(2, \iterator_count($batch));
        $this->assertInstanceOf(Label::class, \iterator_to_array($batch)[0]);
    }
}

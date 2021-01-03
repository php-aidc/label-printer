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

use PhpAidc\LabelPrinter\Compiler;
use PhpAidc\LabelPrinter\Label\Batch;
use PhpAidc\LabelPrinter\Label\Label;
use PhpAidc\LabelPrinter\Label\Element;
use PhpAidc\LabelPrinter\Contract\Command;
use PhpAidc\LabelPrinter\Contract\Language;
use PhpAidc\LabelPrinter\Contract\Label as LabelContract;
use PHPUnit\Framework\TestCase;

class CompilerTest extends TestCase
{
    public function testCompileLabel(): void
    {
        $compiler = Compiler::create(new LanguageA());

        $label = Label::create()->add(Element::raw(''));
        self::assertEquals('ASIZE|ACMD|APRINT1|', $compiler->compile($label));

        $label->copies(2);
        self::assertEquals('ASIZE|ACMD|APRINT2|', $compiler->compile($label));
    }

    public function testCompileBatch(): void
    {
        $compiler = Compiler::create(new LanguageA());

        $label = Label::create()->add(Element::raw(''));
        $batch = new Batch();

        $batch->add(clone $label);
        $batch->add((clone $label)->copies(2));

        self::assertEquals('ASIZE|ACMD|APRINT1|ASIZE|ACMD|APRINT2|', $compiler->compile($batch));
    }

    public function testLanguageCondition(): void
    {
        $label = Label::create()
            ->for(LanguageA::class, static function (Label $label) {
                $label->add(Element::raw(''));
            })
            ->for(LanguageB::class, static function (Label $label) {
                $label->add(Element::raw(''));
            });

        self::assertEquals('ASIZE|ACMD|APRINT1|', (new Compiler(new LanguageA()))->compile($label));
        self::assertEquals('BSIZE|BCMD|BPRINT1|', (new Compiler(new LanguageB()))->compile($label));
    }

    public function testBooleanConditionTruthy(): void
    {
        $label = Label::create()
            ->when(1 > 0, static function (Label $label) {
                $label->add(Element::raw(''));
            });

        self::assertEquals('ASIZE|ACMD|APRINT1|', (new Compiler(new LanguageA()))->compile($label));
    }

    public function testBooleanConditionFalsy(): void
    {
        $label = Label::create()
            ->when(0 > 1, static function (Label $label) {
                $label->add(Element::raw(''));
            });

        self::assertEquals('ASIZE|APRINT1|', (new Compiler(new LanguageA()))->compile($label));
    }
}

class LanguageA implements Language
{
    public function compileDeclaration(LabelContract $label): iterable
    {
        yield 'ASIZE|';
    }

    public function isSupport(Command $command): bool
    {
        return true;
    }

    public function compileCommand(Command $command): iterable
    {
        yield 'ACMD|';
    }

    public function compilePrint(int $copies): iterable
    {
        yield "APRINT{$copies}|";
    }
}

class LanguageB implements Language
{
    public function compileDeclaration(LabelContract $label): iterable
    {
        yield 'BSIZE|';
    }

    public function isSupport(Command $command): bool
    {
        return true;
    }

    public function compileCommand(Command $command): iterable
    {
        yield 'BCMD|';
    }

    public function compilePrint(int $copies): iterable
    {
        yield "BPRINT{$copies}|";
    }
}

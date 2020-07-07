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

use PhpAidc\LabelPrinter\Compiler;
use PhpAidc\LabelPrinter\Label\Label;
use PhpAidc\LabelPrinter\Label\Element;
use PhpAidc\LabelPrinter\Contract\Command;
use PhpAidc\LabelPrinter\Contract\Language;
use PhpAidc\LabelPrinter\Contract\Label as LabelContract;
use PHPUnit\Framework\TestCase;

class CompilerTest extends TestCase
{
    public function testCompile(): void
    {
        $compiler = Compiler::create(new LanguageA());

        $label = Label::create()->add(Element::raw(''));

        $this->assertEquals('ASIZE|ACMD|APRINT|', $compiler->compile($label));
    }

    public function testConditionalCompilation(): void
    {
        $label = Label::create()
            ->for(LanguageA::class, static function (Label $label) {
                $label->add(Element::raw(''));
            })
            ->for(LanguageB::class, static function (Label $label) {
                $label->add(Element::raw(''));
            });

        $this->assertEquals('ASIZE|ACMD|APRINT|', Compiler::create(new LanguageA())->compile($label));
        $this->assertEquals('BSIZE|BCMD|BPRINT|', Compiler::create(new LanguageB())->compile($label));
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
        yield 'APRINT|';
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
        yield 'BPRINT|';
    }
}

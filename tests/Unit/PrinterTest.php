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

use PhpAidc\LabelPrinter\Printer;
use PhpAidc\LabelPrinter\Compiler;
use PhpAidc\LabelPrinter\Command\Raw;
use PhpAidc\LabelPrinter\Label\Label;
use PhpAidc\LabelPrinter\Label\Element;
use PhpAidc\LabelPrinter\Contract\Command;
use PhpAidc\LabelPrinter\Contract\Language;
use PhpAidc\LabelPrinter\Connector\ArrayConnector;
use PhpAidc\LabelPrinter\Contract\Label as LabelContract;
use PHPUnit\Framework\TestCase;

class PrinterTest extends TestCase
{
    public function testPrint(): void
    {
        $printer = new Printer($connector = new ArrayConnector(), Compiler::create(new LanguageC()));

        $printer->print(Label::create()->add(Element::raw('TEST')));

        $this->assertEquals('CSIZE|CTEST|CPRINT|', \implode('', $connector->get()));
    }

    public function testSend(): void
    {
        $printer = new Printer($connector = new ArrayConnector(), Compiler::create(new LanguageC()));

        $printer->send('HELLO');

        $this->assertEquals('HELLO', \implode('', $connector->get()));
    }
}

class LanguageC implements Language
{
    public function compileDeclaration(LabelContract $label): iterable
    {
        yield 'CSIZE|';
    }

    public function isSupport(Command $command): bool
    {
        return true;
    }

    public function compileCommand(Command $command): iterable
    {
        if ($command instanceof Raw) {
            yield from (new LanguageCRawHandler())->translate($command);
        } else {
            yield 'CCMD|';
        }
    }

    public function compilePrint(int $copies): iterable
    {
        yield 'CPRINT|';
    }
}

class LanguageCRawHandler
{
    public function translate(Raw $command): iterable
    {
        $data = $command->getData();

        if ($data instanceof \Traversable) {
            $data = \iterator_to_array($data);
        }

        yield from \array_map(static function ($item) {
            return "C{$item}|";
        }, $data);
    }
}

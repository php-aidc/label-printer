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

namespace PhpAidc\LabelPrinter;

use PhpAidc\LabelPrinter\Label\Batch;
use PhpAidc\LabelPrinter\Contract\Job;
use PhpAidc\LabelPrinter\Contract\Label;
use PhpAidc\LabelPrinter\Contract\Command;
use PhpAidc\LabelPrinter\Contract\Language;
use PhpAidc\LabelPrinter\Label\BooleanCondition;
use PhpAidc\LabelPrinter\Label\LanguageCondition;

final class Compiler
{
    /** @var Language */
    private $language;

    public static function create(Language $language): self
    {
        return new self($language);
    }

    public function __construct(Language $language)
    {
        $this->language = $language;
    }

    public function compile(Job $job): string
    {
        $instructions = [];

        if ($job instanceof Label) {
            $instructions[] = $this->compileLabel($job);
        }

        if ($job instanceof Batch) {
            $instructions[] = $this->compileBatch($job);
        }

        $payload = \array_reduce($instructions, static function ($carry, $item) {
            return $item instanceof \Generator
                ? \array_merge($carry, \iterator_to_array($item, false))
                : \array_merge($carry, $item);
        }, []);

        return \implode($payload);
    }

    private function compileBatch(Batch $batch): \Generator
    {
        foreach ($batch as $label) {
            yield from $this->compileLabel($label);
        }
    }

    private function compileLabel(Label $label): \Generator
    {
        yield from $this->language->compileDeclaration($label);

        yield from $this->compileStatements($label);

        yield from $this->language->compilePrint($label->getCopies());
    }

    private function compileStatements(Label $label): \Generator
    {
        foreach ($label as $statement) {
            yield from $this->compileStatement($label, $statement);
        }
    }

    private function compileStatement(Label $label, $statement): \Generator
    {
        if ($statement instanceof LanguageCondition && $statement->isMatch(\get_class($this->language))) {
            yield from $this->compileStatements($statement->apply((clone $label)->erase()));

            return;
        }

        if ($statement instanceof BooleanCondition && $statement->isTruthy()) {
            yield from $this->compileStatements($statement->apply((clone $label)->erase()));

            return;
        }

        if ($statement instanceof Command && $this->language->isSupport($statement)) {
            yield from $this->language->compileCommand($statement);

            return;
        }
    }
}

<?php

/*
 * This file is part of Appwilio LabelPrinter package.
 *
 *  © appwilio (https://appwilio.com)
 *  © JhaoDa (https://github.com/jhaoda)
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

declare(strict_types=1);

namespace PhpAidc\LabelPrinter;

use PhpAidc\LabelPrinter\Language\Fingerprint;
use PhpAidc\LabelPrinter\Language\Tspl;

class CompilerFactory
{
    /**
     * @param  float  $density
     *
     * @return Compiler
     */
    public static function fingerprint(float $density): Compiler
    {
        return new Compiler(new Fingerprint($density));
    }

    /**
     * @return Compiler
     */
    public static function tspl(): Compiler
    {
        return new Compiler(new Tspl());
    }
}

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

namespace PhpAidc\LabelPrinter\Command;

use PhpAidc\LabelPrinter\Contract\Command;

/**
 * Partially or completely clears the print image buffer.
 */
final class Clear implements Command
{
    /** @var string|null */
    private $field;

    /**
     * @param  string|null  $field  the field from which the print image buffer
     *                              should be cleared (Fingerprint only)
     */
    public function __construct(?string $field = null)
    {
        $this->field = $field;
    }

    public function getField(): ?string
    {
        return $this->field;
    }
}

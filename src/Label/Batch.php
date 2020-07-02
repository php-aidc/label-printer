<?php

/**
 * This file is part of Appwilio LabelPrinter package.
 *
 *  © appwilio (https://appwilio.com)
 *  © JhaoDa (https://github.com/jhaoda)
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

declare(strict_types=1);

namespace PhpAidc\LabelPrinter\Label;

use PhpAidc\LabelPrinter\Contract\Job;
use PhpAidc\LabelPrinter\Contract\Label as LabelContract;

final class Batch implements Job
{
    private $labels = [];

    public function add(LabelContract $label)
    {
        $this->labels[] = $label;

        return $this;
    }

    /**
     * @return LabelContract[]
     */
    public function getLabels()
    {
        return $this->labels;
    }
}

<?php

declare(strict_types=1);

namespace App\Temporal;

use Spiral\TemporalBridge\DeclarationLocatorInterface;

class WorkflowBootloader extends \Spiral\Boot\Bootloader\Bootloader
{
    protected const BINDINGS = [
        DeclarationLocatorInterface::class => SimpleLocator::class,
    ];
}

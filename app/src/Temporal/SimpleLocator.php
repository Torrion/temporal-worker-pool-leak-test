<?php

declare(strict_types=1);

namespace App\Temporal;

use Spiral\TemporalBridge\DeclarationLocatorInterface;
use Temporal\Activity\ActivityInterface;
use Temporal\Workflow\WorkflowInterface;

class SimpleLocator implements DeclarationLocatorInterface
{

    public function getDeclarations(): iterable
    {
        yield WorkflowInterface::class => new \ReflectionClass(ManyTasksWorkflow::class);
        yield ActivityInterface::class => new \ReflectionClass(TestActivity::class);
    }
}

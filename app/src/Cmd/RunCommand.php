<?php

declare(strict_types=1);

namespace App\Cmd;

use App\Temporal\ManyTasksWorkflow;
use Spiral\Console\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Temporal\Client\WorkflowClientInterface;
use Temporal\Client\WorkflowOptions;
use Temporal\Common\RetryOptions;

class RunCommand extends Command
{
    protected const NAME = 'long_workflow:run';

    public function __construct(
        private WorkflowClientInterface $workflowClient
    )
    {
        parent::__construct();
    }

    public function perform(
        WorkflowClientInterface $workflowClient
    )
    {
        $stub = $workflowClient
            ->newWorkflowStub(
                ManyTasksWorkflow::class,
                WorkflowOptions::new()
                    ->withRetryOptions(
                        RetryOptions::new()
                            ->withMaximumAttempts(2)
                    )
            );

        $workflowClient
            ->start(
                $stub,
                []
            );
    }
}

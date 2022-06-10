<?php

declare(strict_types=1);

namespace App\Temporal;

use Temporal\Activity\ActivityOptions;
use Temporal\Common\RetryOptions;
use Temporal\Workflow;
use Temporal\Workflow\WorkflowInterface;
use Temporal\Workflow\WorkflowMethod;

#[WorkflowInterface()]
class ManyTasksWorkflow
{
    #[WorkflowMethod(name: 'many_action_test')]
    public function run()
    {
        for ($i = 0; $i < 100000; $i++)
        {
            yield Workflow::newActivityStub(
                    TestActivity::class,
                    ActivityOptions::new()
                        ->withRetryOptions(
                            RetryOptions::new()
                                ->withMaximumAttempts(5)

                        )
                        ->withScheduleToStartTimeout('2 minute')
                        ->withStartToCloseTimeout('5 minute')
                )
                ->exec()
            ;
        }
    }
}

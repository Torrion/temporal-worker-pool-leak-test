<?php

declare(strict_types=1);

namespace App\Temporal;

use Temporal\Activity\ActivityInterface;

#[ActivityInterface]
class TestActivity
{
    public function exec(): string
    {
        usleep(
            random_int(300,900) * 1000
        );

        if (random_int(0,100) >= 80)
        {
            throw new \Exception('test exception');
        }

        return 'done';
    }
}

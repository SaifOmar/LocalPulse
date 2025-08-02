<?php

namespace App\Jobs;

use App\Media\MediaProcessor;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ProcessMedia implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    // should also take a media provider to store the file
    public function __construct(public MediaProcessor $processor) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->processor->process();
    }
}

<?php

namespace App\Jobs;

use App\Models\Interaction;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ProcessInteraction implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public array $data)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Interaction::updateOrCreate(
            [
                'account_id' => $this->data['account_id'],
                'pulse_id' => $this->data['pulse_id'],
                'type' => $this->data['type']
            ],
            [
                'delta' => 0,
                'meta' => $this->data['meta'] ?? []
            ]
        )->increment('delta', $this->data['delta'] ?? 1);
    }
}

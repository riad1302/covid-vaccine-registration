<?php

namespace App\Jobs;

use App\Models\User;
use App\Services\RegisterUserService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\SerializesModels;

class RegisterUserJob implements ShouldQueue
{
    use Queueable,SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(private User $user)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        app(RegisterUserService::class)->handle($this->user);
    }
}

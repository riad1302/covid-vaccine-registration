<?php

namespace App\Jobs;

use App\Mail\VaccinationDateNotifyMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class VaccinationDateNotifyJob implements ShouldQueue
{
    use Queueable, SerializesModels;

    private array $mail;

    /**
     * Create a new job instance.
     */
    public function __construct(array $emailInfo)
    {
        $this->mail = $emailInfo;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $email = new VaccinationDateNotifyMail($this->mail);
        Mail::to($this->mail['email'])->send($email);
    }
}

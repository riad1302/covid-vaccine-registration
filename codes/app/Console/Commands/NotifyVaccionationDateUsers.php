<?php

namespace App\Console\Commands;

use App\Services\VaccinationNotificationService;
use Illuminate\Console\Command;

class NotifyVaccionationDateUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:notify-vaccionation-date-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notify user of vaccination date tommorrow';

    public function __construct(private VaccinationNotificationService $notificationService)
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->notificationService->notifyUsersOfTomorrowVaccinationDate();
        $this->info('Vaccination notification emails dispatched successfully.');
    }
}

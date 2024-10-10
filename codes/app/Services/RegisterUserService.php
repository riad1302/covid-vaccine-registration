<?php

namespace App\Services;

use App\Models\User;
use App\Models\VaccineCenter;
use App\Models\VaccineDate;
use Illuminate\Support\Facades\Log;

class RegisterUserService
{
    public function __construct(
        private VaccinationSchedulerService $vaccinationSchedulerService,
        private VaccinationRedisService $vaccinationRedisService
    ) {}

    /**
     * Handles the registration and scheduling of a user's vaccination date.
     */
    public function handle(User $user): void
    {
        try {
            // Get the next available vaccination date
            $vaccinationDate = $this->vaccinationSchedulerService->getNextAvailableVaccinationDate($user);

            // Create vaccination record in the database
            $vaccineInfo = VaccineDate::create([
                'user_id' => $user->id,
                'vaccine_center_id' => $user->vaccine_center_id,
                'vaccination_date' => $vaccinationDate,
            ]);

            if ($vaccineInfo) {
                $vaccineCenter = VaccineCenter::find($user->vaccine_center_id);

                $redisData = [
                    'vaccine_center_name' => $vaccineCenter->name ?? 'Unknown',
                    'vaccine_center_address' => $vaccineCenter->address ?? 'Unknown',
                    'vaccine_date' => $vaccinationDate,
                ];

                // Store the vaccination date info in Redis
                $this->vaccinationRedisService->storeVaccinationDate($user->nid, $redisData);
            }
        } catch (\Throwable $e) {
            Log::error('RegisterUserService failed: '.$e->getMessage(), ['user_id' => $user->id]);
        }
    }
}

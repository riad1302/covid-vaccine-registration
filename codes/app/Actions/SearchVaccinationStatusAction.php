<?php

namespace App\Actions;

use App\Models\User;
use App\Services\VaccinationRedisService;

class SearchVaccinationStatusAction
{
    public function __construct(private VaccinationRedisService $redisService) {}

    /**
     * Search vaccination status by NID.
     */
    public function execute(array $search): array
    {
        // Fetch vaccination info from Redis
        $userInfo = $this->redisService->fetchVaccinationDate($search['nid']);

        // If not found in Redis, get from the database
        if (empty($userInfo)) {
            $userInfo = $this->getVaccinationDataFromDatabase($search['nid']);
        }

        $currentDate = now()->format('Y-m-d');

        // Determine the vaccination status
        if (! empty($userInfo) && ! empty($userInfo['vaccine_date']) && strtotime($userInfo['vaccine_date']) < strtotime($currentDate)) {
            return [
                'status' => 'Vaccinated',
            ];
        } elseif (! empty($userInfo)) {
            return [
                'status' => 'Scheduled',
                'vaccine_center_info' => [
                    'name' => $userInfo['vaccine_center_name'],
                    'address' => $userInfo['vaccine_center_address'],
                    'date' => $userInfo['vaccine_date'],
                ],
            ];
        } else {
            return [
                'status' => 'Not Registered',
            ];
        }
    }

    /**
     * Retrieve vaccination data from the database.
     */
    private function getVaccinationDataFromDatabase(string $nid): array
    {
        $userInfo = User::with([
            'vaccine_date:id,user_id,vaccination_date',
            'vaccine_center:id,name,address',
        ])->where('nid', $nid)->first();

        if (! $userInfo) {
            return [];
        }

        return [
            'vaccine_center_name' => $userInfo->vaccine_center->name ?? null,
            'vaccine_center_address' => $userInfo->vaccine_center->address ?? null,
            'vaccine_date' => $userInfo->vaccine_date->vaccination_date ?? null,
        ];
    }
}

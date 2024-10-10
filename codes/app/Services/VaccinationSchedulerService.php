<?php

namespace App\Services;

use App\Models\User;
use App\Models\VaccineCenter;
use App\Models\VaccineDate;

class VaccinationSchedulerService
{
    /**
     * Get the next available vaccination date for the user.
     */
    public function getNextAvailableVaccinationDate(User $user)
    {
        // Get the current date
        $today = date('Y-m-d');

        // Fetch the user's latest vaccination record
        $latestVaccineRecord = VaccineDate::where('vaccine_center_id', $user->vaccine_center_id)
            ->latest('vaccination_date')
            ->first();

        // Default to today's date if no vaccination records exist
        $scheduledDate = $today;

        if ($latestVaccineRecord) {
            $vaccineCenter = VaccineCenter::find($user->vaccine_center_id);
            $scheduledDate = $latestVaccineRecord->vaccination_date;

            // If the vaccination date is in the future, check the availability
            if (strtotime($today) < strtotime($scheduledDate)) {
                $registrationsCount = VaccineDate::where('vaccine_center_id', $user->vaccine_center_id)
                    ->where('vaccination_date', $scheduledDate)
                    ->count();

                // If the vaccine center is fully booked, move to the next available date
                if ($registrationsCount >= $vaccineCenter->daily_capacity) {
                    $scheduledDate = $this->findNextAvailableDate($scheduledDate, true);
                }
            }
        }

        // Ensure the vaccination date falls on a valid weekday
        return $this->findNextAvailableDate($scheduledDate, strtotime($today) >= strtotime($scheduledDate));
    }

    /**
     * Find the next available date that is not a Friday or Saturday.
     */
    private function findNextAvailableDate($date, $increment = true)
    {
        $dateTimestamp = strtotime($date);

        do {
            if ($increment) {
                $dateTimestamp = strtotime('+1 day', $dateTimestamp);
            }
            $dayOfWeek = date('N', $dateTimestamp);
        } while (in_array($dayOfWeek, [5, 6])); // Skip Friday (5) and Saturday (6)

        return date('Y-m-d', $dateTimestamp);
    }
}

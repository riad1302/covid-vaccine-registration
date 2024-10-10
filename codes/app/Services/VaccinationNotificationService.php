<?php

namespace App\Services;

use App\Jobs\VaccinationDateNotifyJob;
use App\Models\VaccineDate;
use Carbon\Carbon;

class VaccinationNotificationService
{
    public function notifyUsersOfTomorrowVaccinationDate(): void
    {
        $tomorrow = Carbon::tomorrow()->toDateString();

        // Fetch users scheduled for vaccination tomorrow
        $userInfo = VaccineDate::with('user:id,name,email,mobile_number', 'vaccine_center:id,name,address')
            ->whereDate('vaccination_date', $tomorrow)
            ->get();

        // Early return if no users are found
        if ($userInfo->isEmpty()) {
            return; // No users to notify
        }

        // Prepare and dispatch email jobs
        $userInfo->each(function ($info) {
            $emailInfo = [
                'email' => $info->user->email,
                'name' => $info->user->name,
                'mobile_number' => $info->user->mobile_number,
                'vaccine_center_name' => $info->vaccine_center->name,
                'vaccine_center_address' => $info->vaccine_center->address,
                'vaccination_date' => Carbon::parse($info->vaccination_date)->format('d F Y'),
            ];
            // Dispatch the email job
            dispatch(new VaccinationDateNotifyJob($emailInfo));
        });
    }
}

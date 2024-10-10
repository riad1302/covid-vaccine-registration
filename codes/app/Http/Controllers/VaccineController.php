<?php

namespace App\Http\Controllers;

use App\Services\VaccinationNotificationService;

class VaccineController extends Controller
{
    public function __construct(private VaccinationNotificationService $service) {}

    public function notifyVaccinations()
    {
        $this->service->notifyUsersOfTomorrowVaccinationDate();

        return response()->json(['message' => 'Vaccination emails dispatched.']);
    }
}

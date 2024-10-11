<?php

namespace Tests\Unit\App\Services;

use App\Services\VaccinationSchedulerService;
use Carbon\Carbon;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VaccinationSchedulerServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_next_available_vaccination_date()
    {
        $user = UserFactory::new()->createOne();
        $vaccinationScheduleService = $this->app->make(VaccinationSchedulerService::class);
        $response = $vaccinationScheduleService->getNextAvailableVaccinationDate($user);

        $this->assertNotEmpty($response);
        $this->assertGreaterThan(now(), $response);

    }

    public function test_next_available_week_date_is_weekday_and_after_next_friday()
    {
        $today = Carbon::now();
        $nextFriday = $today->next(Carbon::FRIDAY)->toDateString();

        $vaccinationScheduleService = $this->app->make(VaccinationSchedulerService::class);
        $reflection = new \ReflectionClass($vaccinationScheduleService);
        $method = $reflection->getMethod('findNextAvailableDate');
        $method->setAccessible(true); // Make the private method accessible

        // Call the private method and pass the required parameters
        $response = $method->invoke($vaccinationScheduleService, $nextFriday, true);

        $this->assertNotEquals($nextFriday, $response);
        $this->assertGreaterThan($nextFriday, $response);

        $responseDate = Carbon::parse($response);
        $this->assertFalse($responseDate->isFriday());
        $this->assertFalse($responseDate->isSaturday());
    }
}

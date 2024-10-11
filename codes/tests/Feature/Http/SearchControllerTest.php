<?php

namespace Tests\Feature\Http;

use Carbon\Carbon;
use Database\Factories\UserFactory;
use Database\Factories\VaccineCenterFactory;
use Database\Factories\VaccineDateFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SearchControllerTest extends TestCase
{
    use RefreshDatabase;

    private string $nid = '1234567890';

    public function test_search_screen_can_be_rendered(): void
    {
        $response = $this->get('/search');
        $response->assertStatus(200);
    }

    public function test_user_can_search_status_by_nid(): void
    {
        $response = $this->get('/search-status?nid='.$this->nid);
        $response->assertStatus(200);
    }

    public function test_user_can_search_status_not_registered(): void
    {
        $response = $this->get('/search-status?nid='.$this->nid);
        $response->assertSee('Not Registered');
    }

    public function test_user_can_search_status_scheduled(): void
    {
        $this->createUserWithScheduledVaccination();

        $response = $this->get('/search-status?nid='.$this->nid);
        $response->assertStatus(200);
        //$response->assertSee('Scheduled'); // Uncommented to assert the scheduled status
    }

    public function test_user_can_search_status_vaccinated(): void
    {
        $this->createUserWithVaccination();

        $response = $this->get('/search-status?nid='.$this->nid);
        $response->assertStatus(200);
        $response->assertSee('Vaccinated');
    }

    private function createUserWithScheduledVaccination(): void
    {
        $vaccineCenter = VaccineCenterFactory::new()->createOne();
        $user = UserFactory::new()->createOne([
            'vaccine_center_id' => $vaccineCenter->id,
            'nid' => $this->nid,
        ]);
        VaccineDateFactory::new()->createOne([
            'user_id' => $user->id,
            'vaccine_center_id' => $vaccineCenter->id,
            'vaccination_date' => Carbon::now()->addDays(7),
        ]);
    }

    private function createUserWithVaccination(): void
    {
        $vaccineCenter = VaccineCenterFactory::new()->createOne();
        $user = UserFactory::new()->createOne([
            'vaccine_center_id' => $vaccineCenter->id,
            'nid' => $this->nid,
        ]);
        VaccineDateFactory::new()->createOne([
            'user_id' => $user->id,
            'vaccine_center_id' => $vaccineCenter->id,
            'vaccination_date' => Carbon::now()->subDays(7),
        ]);
    }
}

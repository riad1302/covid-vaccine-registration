<?php

namespace Tests\Feature\Auth;

use Database\Factories\VaccineCenterFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        $vaccineCenter = VaccineCenterFactory::new()->createOne();
        $response = $this->post('/register', [
            'name' => 'Test User',
            'vaccine_center_id' => $vaccineCenter->id,
            'email' => 'test@example.com',
            'mobile_number' => '01829999999',
            'nid' => '1234567891',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertSessionHas('success', 'Registration successful');
        $response->assertRedirect(route('register'));
    }
}

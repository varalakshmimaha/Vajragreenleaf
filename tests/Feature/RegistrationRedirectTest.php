<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class RegistrationRedirectTest extends TestCase
{
    use RefreshDatabase; // Use in-memory database or transaction rollback

    public function test_registration_redirects_to_user_dashboard()
    {
        $userData = [
            'name' => 'Test User',
            'mobile' => '9876543210',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        $response = $this->postJson(route('auth.register.submit'), $userData);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                 ]);
        
        $redirectUrl = $response->json('redirect_url');
        
        $this->assertEquals(route('user.dashboard'), $redirectUrl, "Redirect URL should be user dashboard");
    }
}

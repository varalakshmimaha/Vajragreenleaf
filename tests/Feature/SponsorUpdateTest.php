<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class SponsorUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_add_sponsor_if_none_exists()
    {
        // Create a potential sponsor
        $sponsor = User::factory()->create([
            'referral_id' => '12345',
            'username' => 'SPONSOR1',
        ]);

        // Create a user without a sponsor
        $user = User::factory()->create([
            'sponsor_id' => null,
            'sponsor_referral_id' => null,
        ]);

        $this->actingAs($user);

        // Attempt to add sponsor
        $response = $this->put(route('user.sponsor.update'), [
            'sponsor_id' => '12345',
        ]);

        $response->assertSessionHas('success');
        
        $user->refresh();
        $this->assertEquals('12345', $user->sponsor_referral_id);
    }

    public function test_user_cannot_update_sponsor_if_already_exists()
    {
        // Create user with sponsor
        $user = User::factory()->create([
            'sponsor_referral_id' => '12345',
        ]);

        $this->actingAs($user);

        $response = $this->put(route('user.sponsor.update'), [
            'sponsor_id' => '67890',
        ]);

        $response->assertSessionHasErrors('sponsor_id');
        
        $user->refresh();
        $this->assertEquals('12345', $user->sponsor_referral_id);
    }

    public function test_user_cannot_sponsor_themselves()
    {
        $user = User::factory()->create([
            'referral_id' => 'MYID123',
            'sponsor_referral_id' => null,
        ]);

        $this->actingAs($user);

        $response = $this->put(route('user.sponsor.update'), [
            'sponsor_id' => 'MYID123',
        ]);

        $response->assertSessionHasErrors('sponsor_id');
    }
}

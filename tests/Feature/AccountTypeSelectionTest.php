<?php
namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccountTypeSelectionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function new_user_is_redirected_to_account_type_selection()
    {
        $user = User::factory()->create(['account_type' => null]);
        $this->actingAs($user)
            ->get('/dashboard')
            ->assertRedirect(route('account.type.select'));
    }

    /** @test */
    public function demo_user_bypasses_account_type_selection()
    {
        $user = User::factory()->create(['is_demo' => true, 'account_type' => null]);
        $this->actingAs($user)
            ->get('/dashboard')
            ->assertStatus(200);
    }

    /** @test */
    public function completed_profile_bypasses_account_type_selection()
    {
        $user = User::factory()->create(['account_type' => 'restaurant']);
        $this->actingAs($user)
            ->get('/dashboard')
            ->assertStatus(200);
    }

    /** @test */
    public function ajax_request_gets_json_error_if_account_type_missing()
    {
        $user = User::factory()->create(['account_type' => null]);
        $this->actingAs($user)
            ->getJson('/dashboard')
            ->assertStatus(403)
            ->assertJson(['error' => 'Account type selection required.']);
    }

    /** @test */
    public function account_type_selection_form_validates_allowed_types()
    {
        $user = User::factory()->create(['account_type' => null]);
        $this->actingAs($user)
            ->post(route('account.type.save'), ['account_type' => 'invalid'])
            ->assertSessionHasErrors('account_type');
    }

    /** @test */
    public function account_type_selection_form_saves_valid_type()
    {
        $user = User::factory()->create(['account_type' => null]);
        $this->actingAs($user)
            ->post(route('account.type.save'), ['account_type' => 'waiter'])
            ->assertRedirect(route('dashboard'));
        $this->assertEquals('waiter', $user->fresh()->account_type);
    }
}

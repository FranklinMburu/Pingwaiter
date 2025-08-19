<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Invitation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class InvitationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_send_invitation()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->withSession(['_token' => csrf_token()]);
        $this->actingAs($admin)
            ->post(route('invitations.store'), [
                'email' => 'worker@example.com',
                'role' => 'waiter',
                '_token' => csrf_token(),
            ])
            ->assertSessionHas('success');

        $this->assertDatabaseHas('invitations', [
            'email' => 'worker@example.com',
            'role' => 'waiter',
            'status' => 'pending',
        ]);
    }

    public function test_invitation_can_be_resend_and_revoked()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $invitation = Invitation::create([
            'email' => 'worker2@example.com',
            'role' => 'cashier',
            'token' => Str::random(40),
            'inviter_id' => $admin->id,
            'expires_at' => now()->addDay(),
        ]);
        $this->withSession(['_token' => csrf_token()]);
        $this->actingAs($admin)
            ->post(route('invitations.resend', $invitation->id), ['_token' => csrf_token()])
            ->assertSessionHas('success');

        $this->actingAs($admin)
            ->post(route('invitations.revoke', $invitation->id), ['_token' => csrf_token()])
            ->assertSessionHas('success');

        $this->assertDatabaseHas('invitations', [
            'id' => $invitation->id,
            'status' => 'revoked',
        ]);
    }

    public function test_expired_invitation_cannot_be_accepted()
    {
        $invitation = Invitation::create([
            'email' => 'expired@example.com',
            'role' => 'cook',
            'token' => Str::random(40),
            'inviter_id' => null,
            'expires_at' => now()->subDay(),
        ]);
        $this->get(route('invitations.accept', $invitation->token))
            ->assertSee('Invitation Link Expired');
    }
}

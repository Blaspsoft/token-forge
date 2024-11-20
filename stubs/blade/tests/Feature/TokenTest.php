<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Inertia\Testing\AssertableInertia as Assert;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TokenTest extends TestCase
{
    use RefreshDatabase;

    public function test_display_token_management_page()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/api-tokens');

        $response->assertOk()
             ->assertSee('Manage API Tokens')
             ->assertViewHas('tokens');
    }

    public function test_user_can_create_token()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $tokenData = [
            'name' => 'Test Token',
            'permissions' => ['create', 'read'],
        ];

        $response = $this->post('/api-tokens', $tokenData);

        $response->assertStatus(302);
        $response->assertRedirect(route('api-tokens.index'));

        $this->assertDatabaseHas('personal_access_tokens', [
            'name' => 'Test Token',
            'tokenable_id' => $user->id,
        ]);
    }

    public function test_user_can_update_token_permissions()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $token = $user->createToken('Initial Token', ['read']);
        $tokenId = $token->accessToken->id;

        $updatedTokenData = [
            'permissions' => ['read', 'update'],
        ];

        $response = $this->put(route('api-tokens.update', ['token' => $tokenId]), $updatedTokenData);

        $response->assertStatus(302);
        $response->assertRedirect(route('api-tokens.index'));

        $this->assertDatabaseHas('personal_access_tokens', [
            'id' => $tokenId,
            'name' => 'Initial Token',
            'abilities' => json_encode(['read', 'update']),
        ]);
    }

    public function test_user_can_delete_token()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $token = $user->createToken('Delete Token', ['read']);
        $tokenId = $token->accessToken->id;

        $response = $this->delete(route('api-tokens.destroy', ['token' => $tokenId]));

        $response->assertStatus(302);

        $this->assertDatabaseMissing('personal_access_tokens', [
            'id' => $tokenId,
        ]);
    }
}

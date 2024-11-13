<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TokenTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that the token management page displays correctly.
     */
    public function test_display_token_management_page()
    {
        // Create a user and authenticate using Sanctum
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        // Hit the token management page
        $response = $this->get(route('api-tokens.index'));

        // Assert the page is rendered with a successful response
        $response->assertStatus(200);
        $response->assertViewIs('api-tokens.index'); // Adjust view name if necessary
        $response->assertSee('API Token Management');
    }

    /**
     * Test that a user can create a token.
     */
    public function test_user_can_create_token()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        // Request payload
        $tokenData = [
            'name' => 'Test Token',
            'abilities' => ['create', 'read'],
        ];

        // Post request to create a token
        $response = $this->post(route('api-tokens.store'), $tokenData);

        // Assert the token was created and response status is 201
        $response->assertStatus(201);
        $this->assertDatabaseHas('personal_access_tokens', [
            'name' => 'Test Token',
            'tokenable_id' => $user->id,
        ]);
    }

    /**
     * Test that a user can update token permissions.
     */
    public function test_user_can_update_token_permissions()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        // Create a token
        $token = $user->createToken('Initial Token', ['read'])->plainTextToken;

        // Update token abilities
        $updatedTokenData = [
            'abilities' => ['read', 'update'],
        ];

        // Send put request to update token
        $response = $this->put(route('api-tokens.update', ['token' => $token]), $updatedTokenData);

        // Assert token abilities were updated
        $response->assertStatus(200);
        $this->assertDatabaseHas('personal_access_tokens', [
            'name' => 'Initial Token',
            'abilities' => json_encode(['read', 'update']),
        ]);
    }

    /**
     * Test that a user can delete a token.
     */
    public function test_user_can_delete_token()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        // Create a token to delete
        $token = $user->createToken('Delete Token', ['read'])->plainTextToken;

        // Send delete request for the token
        $response = $this->delete(route('api-tokens.destroy', ['token' => $token]));

        // Assert token was deleted
        $response->assertStatus(200);
        $this->assertDatabaseMissing('personal_access_tokens', [
            'id' => $token->id,
        ]);
    }
}

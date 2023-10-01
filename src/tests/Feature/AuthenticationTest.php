<?php 
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_sign_in_a_user_with_valid_credentials()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $userData = [
            'email' => 'test@example.com',
            'password' => 'password',
        ];

        $response = $this->postJson('/api/signin', $userData);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'token',
                    'name',
                ],
            ]);
    }

    /** @test */
    public function it_cannot_sign_in_a_user_with_invalid_credentials()
    {
        $userData = [
            'email' => 'test@example.com',
            'password' => 'invalidpassword',
        ];

        $response = $this->postJson('/api/signin', $userData);

        $response->assertStatus(404)
            ->assertJson([
                'message' => 'Unauthorised',
            ]);
    }

    /** @test */
    public function it_can_sign_up_a_new_user()
    {
        $userData = [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'role' => 'user',
            'password' => 'password',
            'confirm_password' => 'password',
        ];

        $response = $this->postJson('/api/signup', $userData);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'token',
                    'name',
                ],
            ]);

        $this->assertDatabaseHas('users', [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
        ]);
    }

    /** @test */
    public function it_can_sign_out_a_user()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->postJson('/api/signout');

        $response->assertStatus(200);

        $this->assertEmpty($user->tokens);
    }
}

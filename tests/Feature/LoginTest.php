<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class LoginTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_login()
    {
        $password = 'password';

        // Creating Users
        User::create([
            'name' => 'Test',
            'email' => $email = time() . '@example.com',
            'password' => Hash::make($password)
        ]);

        // Logging In
        $response = $this->post(route('login'), [
            'email' => $email,
            'password' => $password,
        ]);

        // Determine whether the login is successful and receive token
        $response->assertStatus(200);

        // Delete users
        User::where('email', $email)->delete();

    }
}

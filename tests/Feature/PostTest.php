<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\TestCase;

class PostTest extends TestCase
{
    /**
     * A basic feature test example.
      @return void
     */
    private function getToken()
    {
        $password = 'password';

        // Creating Users
        User::create([
            'name' => 'Test',
            'email' => $email = Str::random(6) . '@example.com',
            'password' => Hash::make($password)
        ]);

        // Logging In
        $response = $this->post(route('login'), [
            'email' => $email,
            'password' => $password,
        ]);

        return $response['data']['token'];
    }

    public function test_post_image_is_required()
    {
        $token = $this->getToken();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept'=>'application/json'

        ])->post(route('posts.store'), [

            'title' => 'This is post title',
            'description' => 'This is post description',
            'image' => '' // intentionally left blank to show validation error
        ]);

        $response->assertStatus(422);
    }

}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;

class ApiAuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {

            $this->data['token'] = Auth::user()->createToken('Api_token')->plainTextToken;

            $this->has_err = false;

        } else {
            $this->message = 'Invalid credentials';
        }

        return $this->sendResponse();

    }
}

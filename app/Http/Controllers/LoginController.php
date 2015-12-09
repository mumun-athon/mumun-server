<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;

class LoginController extends Controller
{
    /**
     * Instance of controller
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth', ['only' => 'logout']);
    }

    /**
     * Do login.
     *
     * @return json
     */
    public function login(Request $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');

        $attempt = Auth::attempt(['email' => $email, 'password' => $password]);

        if ($attempt)
        {
            return [
                'status' => 'ok',
                'error' => false,
                'user' => Auth::user()
            ];
        }

        return [
            'status' => 'failed',
            'error' => true,
        ];
    }

    /**
     * Logout an user.
     *
     * @return json
     */
    public function logout()
    {
        Auth::logout();

        return ['logout' => true];
    }
}

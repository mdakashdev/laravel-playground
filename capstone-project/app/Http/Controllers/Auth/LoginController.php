<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index(LoginRequest $request)
    {
        $request->validated();

        $attempt = Auth::attempt($request->validated());

        //1. Registration - user details - so need user tables, migration, filed, models
        //1. a) laravel by default user er table, migration & model pai.
        //1. b) need data - data 3 vabe dite pari - form, api, seeder
        //1. c) ami seed use korlam
        //2. For login need user & password
        //3. first - validate then user & pass credential pass then find user - session create - return true

        dd(Auth::user());

        return "login page from controller - ". $attempt;
    }
}

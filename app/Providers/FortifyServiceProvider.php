<?php

namespace App\Providers;

use App\Http\Responses\LoginResponse;
use App\Http\Responses\RegisterResponse;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;


public function register()
{
    $this->app->singleton(LoginResponseContract::class, LoginResponse::class);
    $this->app->singleton(RegisterResponseContract::class, RegisterResponse::class);
}

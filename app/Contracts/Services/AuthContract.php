<?php

namespace App\Contracts\Services;

interface AuthContract
{
    public function login($request);
    public function me();
    public function refresh();
    public function logout();
    public function register($request);
}

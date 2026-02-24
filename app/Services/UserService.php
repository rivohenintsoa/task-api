<?php

namespace App\Services;

use App\Models\User;

class UserService
{
    public function getUsers()
    {
        return User::select('id', 'name', 'email', 'role')->get();
    }
}
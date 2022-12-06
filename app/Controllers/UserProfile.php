<?php

namespace App\Controllers;

use App\View;

class UserProfile
{

    public function index(): View
    {
        return new View("userprofile");
    }

    public function updateUserInfo()
    {

    }

    public function updateUserPassword()
    {

    }
}

<?php

namespace App\Controllers;

use App\Redirect;

class LogoutController
{
    public function logout(): Redirect
    {
        session_destroy();
        return new Redirect('/login');
    }
}
<?php

namespace App\Controllers;

class Home extends BaseController
{
    // Redirect user based on their role after login
    public function index()
    {
        $user = auth('jwt')->user();

        if (!$user) {
            return redirect()->to('/login');
        }

        if ($user->inGroup('admin')) {
            return redirect()->to('/admin/dashboard');
        }

        if ($user->inGroup('student')) {
            return redirect()->to('/student/dashboard');
        }
    }
}

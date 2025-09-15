<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\Shield\Entities\User;
use App\Models\UserModel;

class AddInitialAdminSeeder extends Seeder
{
    public function run()
    {
        $userModel = new UserModel();

        $data = [
            'username'  => 'admin',
            'email'     => 'admin@example.com',
            'full_name' => 'Administrator',
            'password'  => 'admin123',
        ];

        $user = new User($data);
        $user->id = $userModel->insert($user);
        $user->addGroup('admin')->activate();
    }
}

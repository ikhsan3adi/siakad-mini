<?php

namespace App\Database\Seeds;

use App\Models\UserModel;
use CodeIgniter\Database\Seeder;
use CodeIgniter\Shield\Entities\User;

class AddInitialAdminSeeder extends Seeder
{
    private UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function run()
    {
        /** @var User $user */
        $user = $this->userModel->createNewUser([
            'username'  => 'admin',
            'full_name' => 'Administrator',
            'email'     => 'admin@example.com',
            'password'  => 'admin123',
        ]);

        $userId = $this->userModel->insert($user);

        $user->id = $userId;

        $user->addGroup('admin');
        $user->activate();
    }
}

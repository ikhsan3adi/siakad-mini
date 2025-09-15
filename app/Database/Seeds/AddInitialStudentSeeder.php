<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Faker\Provider\Person;
use CodeIgniter\Shield\Entities\User;
use App\Models\UserModel;

class AddInitialStudentSeeder extends Seeder
{
    public function run()
    {
        $faker = \Faker\Factory::create('id_ID');
        $userModel = new UserModel();

        for ($i = 1; $i <= 20; $i++) {
            $gender = $i % 2 == 0 ? Person::GENDER_MALE : Person::GENDER_FEMALE;

            $data = [
                'username'      => 'student' . $i,
                'email'         => 'student' . $i . '@example.com',
                'full_name'     => $faker->firstName($gender) . ' ' . $faker->firstName($gender),
                'password'      => 'student' . $i,
                'entry_year'    => $faker->numberBetween(2022, 2025),
            ];

            $user = new User($data);
            $user->id = $userModel->insert($user);
            $user->addGroup('student')->activate();
        }
    }
}

<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\CourseModel;

class AddInitialCourseSeeder extends Seeder
{
    public function run()
    {
        $courseModel = new CourseModel();

        $courses = [
            [
                'course_code' => '25IF1102',
                'course_name' => 'Dasar-dasar Pemrograman',
                'description' => 'Pengenalan konsep dasar pemrograman komputer.',
                'credits' => 4,
            ],
            [
                'course_code' => '21IF1004',
                'course_name' => 'Struktur Data dan Algoritma',
                'description' => 'Mempelajari struktur data dasar dan algoritma umum.',
                'credits' => 4,
            ],
            [
                'course_code' => '25IF2114',
                'course_name' => 'Basis Data',
                'description' => 'Konsep dan implementasi basis data relasional.',
                'credits' => 4,
            ],
        ];

        foreach ($courses as $course) {
            $courseModel->insert($course);
        }
    }
}

<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\CourseModel;
use App\Models\UserModel;
use CodeIgniter\Shield\Models\GroupModel;

class AddEnrolledCourseSeeder extends Seeder
{
    public function run()
    {
        $courseModel = new CourseModel();
        $userModel = new UserModel();
        $groupModel = new GroupModel();

        // query all students, query all courses then randomly enroll students to courses
        $students = $groupModel->where('group', 'student')->findAll();
        $courses = $courseModel->findAll();

        foreach ($students as $student) {
            // Enroll each student in 1 to 3 random courses
            $numCourses = rand(1, 3);
            $selectedCourses = array_rand($courses, $numCourses);

            if (!is_array($selectedCourses)) {
                $selectedCourses = [$selectedCourses];
            }

            foreach ($selectedCourses as $index) {
                $course = $courses[$index];

                // random gpa between 2.0 to 4.0 or null
                $grade = rand(0, 1) ? number_format(rand(200, 400) / 100, 2) : null;

                $courseModel->enrollStudent(
                    $course['course_code'],
                    $student['user_id'],
                    date('Y-m-d'),
                    $grade
                );
            }
        }
    }
}

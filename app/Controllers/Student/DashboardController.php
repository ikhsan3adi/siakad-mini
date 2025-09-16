<?php

namespace App\Controllers\Student;

use App\Controllers\BaseController;
use App\Models\UserModel;

class DashboardController extends BaseController
{
    public function index()
    {
        $userModel = new UserModel();
        $currentStudent = auth()->user();

        $enrolledCourses = $userModel->getEnrolledCourses($currentStudent->id);
        $completedCourses = array_filter($enrolledCourses, fn($course) => $course['grade'] !== null);

        $totalEnrolledCourses = count($enrolledCourses);
        $totalCompletedCourses = count($completedCourses);
        $totalCredits = array_sum(array_map(fn($course) => $course['credits'], $completedCourses));
        $averageGrade = $totalCompletedCourses > 0 ? array_sum(array_map(fn($course) => $course['grade'], $completedCourses)) / $totalCompletedCourses : 0;

        $data = [
            'totalEnrolledCourses' => $totalEnrolledCourses,
            'totalCompletedCourses' => $totalCompletedCourses,
            'totalCredits' => $totalCompletedCourses ? $totalCredits : 'N/A',
            'averageGrade' => $totalCompletedCourses ? round($averageGrade, 2) : 'N/A',
            'enrolledCourses' => $enrolledCourses,
        ];

        return view('student/dashboard', $data);
    }
}

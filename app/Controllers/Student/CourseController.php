<?php

namespace App\Controllers\Student;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\CourseModel;

class CourseController extends BaseController
{
    protected UserModel $userModel;
    protected CourseModel $courseModel;
    protected int $perPage = 10;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->courseModel = new CourseModel();
    }

    public function index()
    {
        $keyword = request()->getVar('keyword');
        if ($keyword) {
            $courses = $this->courseModel
                ->search($keyword)
                ->paginate($this->perPage, 'courses');
        } else {
            $courses = $this->courseModel
                ->paginate($this->perPage, 'courses');
        }

        $myEnrollments = $this->userModel->getEnrolledCourses(auth()->user()->id);

        // merge enrolled info with all courses
        $enrolledCourseCodes = array_column($myEnrollments, 'course_code');
        foreach ($courses as &$course) {
            $course['enrolled'] = in_array($course['course_code'], $enrolledCourseCodes, true);
        }
        unset($course);

        $currentStudentCredit = array_sum(array_column($myEnrollments, 'credits'));

        $data = [
            'pager' => $this->courseModel->pager,
            'perPage' => $this->perPage,
            'currentPage' => request()->getVar('page_courses') ?? 1,
            'courses' => $courses,
            'currentStudentCredit' => $currentStudentCredit,
        ];

        return view('student/courses/index', $data);
    }

    public function myCourses()
    {
        $currentStudent = auth()->user();

        $keyword = request()->getVar('keyword') ?? '';

        $enrolledCourses = $this->userModel->getEnrolledCourses($currentStudent->id, keyword: $keyword);

        $myEnrollments = $this->userModel->getEnrolledCourses($currentStudent->id);

        $currentStudentCredit = array_sum(array_column($myEnrollments, 'credits'));

        $data = [
            'courses' => $enrolledCourses,
            'currentStudentCredit' => $currentStudentCredit,
        ];

        return view('student/courses/my_courses', $data);
    }

    public function show(string $code)
    {
        $course = $this->courseModel->find($code);

        if (!$course) {
            return redirect()
                ->to('/student/courses')
                ->with('error', "Course with code $code not found.");
        }

        $enrollment = $this->courseModel->findEnrollment($code, auth()->user()->id) ?? [];

        $data = [
            'course' => [
                ...$course,
                ...$enrollment,
            ],
            'enrolled' => !empty($enrollment),
            'previousUrl' => previous_url() ?: base_url('/student/courses'),
        ];

        return view('student/courses/show', $data);
    }

    public function enroll(string $code)
    {
        $currentStudent = auth()->user();

        $course = $this->courseModel->find($code);
        if (!$course) {
            return redirect()
                ->to('/student/courses')
                ->with('error', "Course with code $code not found.");
        }

        $result = $this->courseModel->enrollStudent($code, $currentStudent->id, date('Y-m-d H:i:s'));

        if (!$result) {
            return redirect()
                ->back()
                ->with('error', "Failed to enroll in the course $code.");
        }

        return redirect()
            ->to("/student/courses/my")
            ->with('message', "Successfully enrolled in the course $code.");
    }

    public function unenroll(string $code)
    {
        $currentStudent = auth()->user();

        $course = $this->courseModel->find($code);
        if (!$course) {
            return redirect()
                ->to('/student/courses')
                ->with('error', "Course with code $code not found.");
        }

        $result = $this->courseModel->unenrollStudent($code, $currentStudent->id);

        if (!$result) {
            return redirect()
                ->back()
                ->with('error', "Failed to unenroll from the course $code.");
        }

        return redirect()
            ->to("/student/courses/my")
            ->with('message', "Successfully unenrolled from the course $code.");
    }

    public function bulkEnroll()
    {
        $currentStudent = auth()->user();

        $courseCodes = $this->request->getPost('selected_course_codes');
        if (empty($courseCodes) || !is_array($courseCodes)) {
            return redirect()
                ->back()
                ->with('error', 'No courses selected for enrollment.');
        }

        $enrolledCount = 0;
        $errors = [];
        foreach ($courseCodes as $courseCode) {
            $course = $this->courseModel->find($courseCode);
            if ($course) {
                $result = true;
                try {
                    $result = $this->courseModel->enrollStudent($courseCode, $currentStudent->id, date('Y-m-d H:i:s'));
                } catch (\Exception $e) {
                    $result = false;
                } finally {
                    if (!$result) {
                        $errors[] = "Failed to enroll in the course <strong>{$course['course_name']} ($courseCode)</strong>. It might be due to already being enrolled.";
                    } else {
                        $enrolledCount++;
                    }
                }
            }
        }

        if ($enrolledCount === 0) {
            return redirect()
                ->back()
                ->with('error', 'Failed to enroll in the selected courses.')
                ->with('errors', $errors);
        }

        return redirect()
            ->to("/student/courses/my")
            ->with('message', "Successfully enrolled in $enrolledCount course(s).")
            ->with('errors', $errors);
    }

    public function bulkUnEnroll()
    {
        $currentStudent = auth()->user();

        $courseCodes = $this->request->getPost('selected_course_codes');
        if (empty($courseCodes) || !is_array($courseCodes)) {
            return redirect()
                ->back()
                ->with('error', 'No courses selected for unenrollment.');
        }

        $unenrolledCount = 0;
        $errors = [];
        foreach ($courseCodes as $courseCode) {
            $course = $this->courseModel->find($courseCode);
            if ($course) {
                $result = true;
                try {
                    $result = $this->courseModel->unenrollStudent($courseCode, $currentStudent->id);
                } catch (\Exception $e) {
                    $result = false;
                } finally {
                    if (!$result) {
                        $errors[] = "Failed to unenroll from the course <strong>{$course['course_name']} ($courseCode)</strong>. It might be due to not being enrolled.";
                    } else {
                        $unenrolledCount++;
                    }
                }
            }
        }

        if ($unenrolledCount === 0) {
            return redirect()
                ->back()
                ->with('error', 'Failed to unenroll from the selected courses.')
                ->with('errors', $errors);
        }

        return redirect()
            ->to("/student/courses/my")
            ->with('message', "Successfully unenrolled from $unenrolledCount course(s).")
            ->with('errors', $errors);
    }
}

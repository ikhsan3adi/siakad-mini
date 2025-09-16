<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CourseModel;

class CourseController extends BaseController
{
    protected CourseModel $courseModel;
    protected int $perPage = 10;

    public function __construct()
    {
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

        $data = [
            'pager' => $this->courseModel->pager,
            'perPage' => $this->perPage,
            'currentPage' => request()->getVar('page_courses') ?? 1,
            'courses' => $courses,
        ];

        return view('admin/courses/index', $data);
    }

    public function show(string $code)
    {
        $course = $this->courseModel->find($code);

        $enrolledStudents = $this->courseModel->getEnrolledStudents($code);

        if (!$course) {
            return redirect()
                ->to('/admin/courses')
                ->with('error', 'Course not found.');
        }

        $data = [
            'course' => $course,
            'enrolledStudents' => $enrolledStudents,
        ];

        return view('admin/courses/show', $data);
    }

    public function new()
    {
        return view('admin/courses/form');
    }

    public function create()
    {
        if (!$this->validate([
            'course_code' => 'required|is_unique[courses.course_code]|max_length[10]',
            'course_name' => 'required|max_length[100]',
            'description' => 'permit_empty',
            'credits'     => 'required|integer|greater_than[0]|less_than[10]',
        ])) {
            return redirect()
                ->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $result = $this->courseModel->save([
            'course_code' => $this->request->getPost('course_code'),
            'course_name' => $this->request->getPost('course_name'),
            'description' => $this->request->getPost('description'),
            'credits'     => $this->request->getPost('credits'),
        ]);

        if (!$result) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to create course. Please try again.');
        }

        return redirect()
            ->to('/admin/courses')
            ->with('success', 'Course created successfully.');
    }

    public function edit(string $code)
    {
        $course = $this->courseModel->find($code);
        if (!$course) {
            return redirect()
                ->to('/admin/courses')
                ->with('error', 'Course not found.');
        }
        return view('admin/courses/form', ['course' => $course]);
    }

    public function update(string $code)
    {
        $course = $this->courseModel->find($code);
        if (!$course) {
            return redirect()
                ->to('/admin/courses')
                ->with('error', 'Course not found.');
        }

        if (!$this->validate([
            'course_code' => 'required|max_length[10]|is_unique[courses.course_code,course_code,' . $code . ',course_code]',
            'course_name' => 'required|max_length[100]',
            'description' => 'permit_empty',
            'credits'     => 'required|integer|greater_than[0]|less_than[10]',
        ])) {
            return redirect()
                ->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $result = $this->courseModel->update($code, [
            'course_code' => $this->request->getPost('course_code'),
            'course_name' => $this->request->getPost('course_name'),
            'description' => $this->request->getPost('description'),
            'credits' => $this->request->getPost('credits'),
        ]);

        if (!$result) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to update course. Please try again.');
        }

        return redirect()
            ->to('/admin/courses')
            ->with('success', 'Course updated successfully.');
    }

    public function delete(string $code)
    {
        $course = $this->courseModel->find($code);
        if (!$course) {
            return redirect()
                ->to('/admin/courses')
                ->with('error', 'Course not found.');
        }

        try {
            //! Warning ON DELETE RESTRICT
            $result = $this->courseModel->delete($code);

            if (!$result) {
                return redirect()
                    ->to('/admin/courses')
                    ->with('error', 'Failed to delete course. Please try again.');
            }

            return redirect()
                ->to('/admin/courses')
                ->with('success', 'Course deleted successfully.');
        } catch (\Exception $e) {
            // Handle exception (e.g., foreign key constraint violation)
            return redirect()
                ->to('/admin/courses')
                ->with('error', 'Failed to delete course. It may be linked to enrolled students.');
        }
    }
}

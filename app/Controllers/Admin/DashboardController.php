<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\UserModel;
use App\Models\CourseModel;
use CodeIgniter\Shield\Models\GroupModel;

class DashboardController extends BaseController
{
    public function index()
    {
        $courseModel = new CourseModel();
        // $userModel = new UserModel();
        $groupModel = new GroupModel();

        $data = [
            'total_students' => $groupModel->where('group', 'student')->countAllResults(),
            'total_admins' => $groupModel->where('group', 'admin')->countAllResults(),
            'total_courses' => $courseModel->countAllResults(),
        ];

        return view('admin/dashboard', $data);
    }
}

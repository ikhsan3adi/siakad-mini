<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\Shield\Models\GroupModel;
use CodeIgniter\Shield\Entities\User;
use App\Models\UserModel;
use CodeIgniter\Shield\Exceptions\ValidationException;

class UserController extends BaseController
{
    protected UserModel $userModel;
    protected GroupModel $groupModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->groupModel = new GroupModel();
    }

    public function index()
    {
        $keyword = $this->request->getGet('keyword') ?? '';
        $userType = $this->request->getGet('type') ?? 'student';

        $users = $this->userModel->getUsersByGroup($userType, $keyword);

        $data = [
            'users' => $users,
            'userType' => $userType === 'admin' ? 'Admin' : 'Student',
        ];

        return view('admin/users/index', $data);
    }

    public function show(string|int $id)
    {
        /** @var \CodeIgniter\Shield\Entities\User */
        $user = $this->userModel->withIdentities()->withGroups()->find($id);

        if (!$user) {
            return redirect()
                ->back()
                ->with('error', 'Course not found.');
        }

        $enrolledCourses = $this->userModel->getEnrolledCourses($id);

        $data = [
            'user' => [
                ...$user->toArray(),
                'email' => $user->getIdentities()[0]->secret,
                'groups' => $user->getGroups(),
            ],
            'enrolledCourses' => $enrolledCourses,
        ];

        return view('admin/users/show', $data);
    }

    public function new()
    {
        $userType = $this->request->getGet('type') ?? 'student';

        return view('admin/users/form', ['userType' => $userType]);
    }

    public function create()
    {
        $rules = [
            'full_name' => 'required|min_length[3]|max_length[255]',
            'username'  => 'required|alpha_numeric|min_length[3]|max_length[50]|is_unique[users.username]',
            'email'     => 'required|valid_email|is_unique[auth_identities.secret]',
            'password'  => 'required|min_length[8]|max_length[255]',
            'entry_year' => 'permit_empty|exact_length[4]|numeric',
            'user_type' => 'required|in_list[admin,student]',
        ];

        if (!$this->validate($rules)) {
            return redirect()
                ->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $fullName = $this->request->getPost('full_name');
        $username = $this->request->getPost('username');
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $entryYear = $this->request->getPost('entry_year');
        $userType = $this->request->getPost('user_type');

        $userData = [
            'username'  => $username,
            'email'     => $email,
            'full_name' => $fullName,
            'password'  => $password,
        ];

        if ($userType === 'student' && $entryYear) {
            $userData['entry_year'] = $entryYear;
        }

        try {
            $user = new User($userData);

            $user->id = $this->userModel->insert($user);

            $user->addGroup($userType)->activate();

            return redirect()
                ->to('/admin/users?type=' . $userType)
                ->with('message', 'User created successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'An error occurred while creating the user. Please try again.');
        }
    }

    public function edit(string|int $id)
    {
        $user = $this->userModel->find($id);
        if (!$user) {
            return redirect()
                ->back()
                ->with('error', 'User not found.');
        }

        $data = [
            'user' => [
                ...$user->toArray(),
                'email' => $user->getIdentities()[0]->secret,
                'groups' => $user->getGroups(),
            ],
            'userType' => in_array('admin', $user->getGroups()) ? 'admin' : 'student',
        ];

        return view('admin/users/form', $data);
    }

    public function update(string|int $id)
    {
        /** @var \CodeIgniter\Shield\Entities\User */
        $user = $this->userModel->withIdentities()->withGroups()->find($id);
        if (!$user) {
            return redirect()
                ->to('/admin/users')
                ->with('error', 'User not found.');
        }

        $rules = [
            'full_name' => 'required|min_length[3]|max_length[255]',
            'username'  => 'required|alpha_numeric|min_length[3]|max_length[50]|is_unique[users.username,id,' . $id . ']',
            'email'     => 'required|valid_email|is_unique[auth_identities.secret,user_id,' . $id . ']',
            'password'  => 'permit_empty|min_length[8]|max_length[255]',
            'entry_year' => 'permit_empty|exact_length[4]|numeric',
        ];

        if (!$this->validate($rules)) {
            return redirect()
                ->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $username = $this->request->getPost('username');
        $email = $this->request->getPost('email');
        $fullName = $this->request->getPost('full_name');
        $password = $this->request->getPost('password');
        $entryYear = $this->request->getPost('entry_year');

        $userData = [
            'username'  => $username,
            'email'     => $email,
            'full_name' => $fullName,
        ];

        if ($password) {
            $userData['password'] = $password;
        }

        if ($user->inGroup('student')) {
            $userData['entry_year'] = $entryYear;
        }

        $user->fill($userData);

        try {
            $this->userModel->update($id, $user);

            return redirect()
                ->to('/admin/users?type=' . (in_array('admin', $user->getGroups()) ? 'admin' : $user->getGroups()[0]))
                ->with('message', 'User updated successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'An error occurred while updating the user. Please try again.');
        }
    }

    public function delete(string|int $id)
    {
        /** @var \CodeIgniter\Shield\Entities\User */
        $user = $this->userModel->find($id);
        if (!$user) {
            return redirect()
                ->to('/admin/users')
                ->with('error', 'User not found.');
        }

        if ($user->inGroup('admin')) {
            return redirect()
                ->back()
                ->with('error', 'Cannot delete an admin user.');
        }

        try {
            $result = $this->userModel->delete($id);

            if (!$result) {
                return redirect()
                    ->back()
                    ->with('error', 'Failed to delete user. Please try again.');
            }

            return redirect()
                ->to('/admin/users' . (in_array('admin', $user->getGroups()) ? '?type=admin' : '?type=student'))
                ->with('message', 'User deleted successfully.');
        } catch (\Exception $e) {
            // Handle exception (e.g., foreign key constraint violation)
            return redirect()
                ->back()
                ->with('error', 'Failed to delete user. They may be linked to enrolled courses.');
        }
    }
}

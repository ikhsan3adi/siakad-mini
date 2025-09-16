<?php

declare(strict_types=1);

namespace App\Models;

use CodeIgniter\Shield\Models\UserModel as ShieldUserModel;

class UserModel extends ShieldUserModel
{
    protected function initialize(): void
    {
        parent::initialize();

        $this->allowedFields = [
            ...$this->allowedFields,

            'full_name',
            'entry_year',
        ];
    }

    public function getUsersByGroup(string $groupName = null, string $keyword = ''): array
    {
        return $this->select('users.*, auth_identities.secret as email')->join('auth_groups_users', 'auth_groups_users.user_id = users.id')
            ->join('auth_identities', 'auth_identities.user_id = users.id')
            ->where('auth_groups_users.group', $groupName)
            ->like('full_name', $keyword, insensitiveSearch: true)
            ->asArray()
            ->findAll();
    }

    public function getEnrolledCourses(string $student_id, bool $sortByGrade = false)
    {
        $builder = $this->db->table('student_courses');
        $builder->select('courses.*, student_courses.*');
        $builder->join('courses', 'student_courses.course_code = courses.course_code');
        $builder->where('student_courses.student_id', $student_id);

        if ($sortByGrade) {
            $builder->orderBy('student_courses.grade', 'DESC');
        } else {
            $builder->orderBy('student_courses.enroll_date', 'DESC');
        }

        $query = $builder->get();

        return $query->getResultArray();
    }
}

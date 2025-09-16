<?php

namespace App\Models;

use CodeIgniter\Model;

class CourseModel extends Model
{
    protected $table            = 'courses';
    protected $primaryKey       = 'course_code';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false; // supaya ON DELETE RESTRICT di foreign key bisa jalan
    protected $protectFields    = true;
    protected $allowedFields    = [
        'course_code',
        'course_name',
        'description',
        'credits',
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    public function search(string $keyword)
    {
        return $this
            ->where('deleted_at', null)
            ->like('course_name', $keyword, insensitiveSearch: true)
            ->orLike('description', $keyword, insensitiveSearch: true);
    }

    public function getEnrolledStudents(string $courseCode, bool $sortByGrade = false)
    {
        $builder = $this->db->table('student_courses');
        $builder->select('users.*, auth_identities.secret as email, student_courses.*');
        $builder->join('users', 'student_courses.student_id = users.id');
        $builder->join('auth_identities', 'student_courses.student_id = auth_identities.user_id');
        $builder->where('student_courses.course_code', $courseCode);

        if ($sortByGrade) {
            $builder->orderBy('student_courses.grade', 'DESC');
        } else {
            $builder->orderBy('student_courses.enroll_date', 'DESC');
        }

        $query = $builder->get();

        return $query->getResultArray();
    }

    // Find course with enrollment info for a specific student and course code
    public function findEnrollment(string $code, string|int $studentId)
    {
        $builder = $this->db->table('student_courses')
            ->where('student_courses.course_code', $code)
            ->where('student_courses.student_id', $studentId);

        $query = $builder->get();

        return $query->getRowArray();
    }

    public function enrollStudent(
        string $courseCode,
        int $studentId,
        string $enrollDate,
        string|float|int $grade = null
    ) {
        $data = [
            'course_code'   => $courseCode,
            'student_id'    => $studentId,
            'enroll_date'   => $enrollDate,
            'grade'         => $grade,
        ];

        $builder = $this->db->table('student_courses');

        return $builder->insert($data);
    }
}

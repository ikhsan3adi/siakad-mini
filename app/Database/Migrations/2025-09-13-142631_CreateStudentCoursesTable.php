<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateStudentCoursesTable extends Migration
{
    public function up()
    {
        $fields = [
            'student_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'course_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'enroll_date' => [
                'type' => 'DATETIME',
            ]
        ];

        $this->forge->addField($fields);

        // composite primary key
        $this->forge->addKey(['student_id', 'course_id'], primary: true);

        // referential integrity constraints
        $this->forge->addForeignKey(
            'student_id',
            tableName: 'users',
            tableField: 'id',
            onUpdate: 'CASCADE',
            onDelete: 'CASCADE'
        );
        $this->forge->addForeignKey(
            'course_id',
            tableName: 'courses',
            tableField: 'id',
            onUpdate: 'CASCADE',
            onDelete: 'RESTRICT'
        );

        $this->forge->createTable('student_courses');
    }

    public function down()
    {
        $this->forge->dropTable('student_courses');
    }
}

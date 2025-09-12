<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAttributesToUsers extends Migration
{
    public function up()
    {
        // add additional fields to users table
        $fields = [
            'full_name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'after' => 'username',
            ],
        ];

        $this->forge->addColumn('users', $fields);
    }

    public function down()
    {
        $fields = [
            'full_name',
        ];

        $this->forge->dropColumn('users', $fields);
    }
}

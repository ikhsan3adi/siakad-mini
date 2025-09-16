<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\CourseModel;

class AddInitialCourseSeeder extends Seeder
{
    public function run()
    {
        $courseModel = new CourseModel();

        $courses = [
            [
                'course_code' => '25IF1102',
                'course_name' => 'Dasar-dasar Pemrograman',
                'description' => 'Memperkenalkan konsep dasar pemrograman komputer, termasuk tipe data, struktur kontrol, fungsi, dan pengenalan algoritma. Mahasiswa akan belajar menulis kode sederhana menggunakan bahasa pemrograman populer seperti Python atau JavaScript. Fokus utama adalah pada logika pemrograman dan pemecahan masalah dasar.',
                'credits' => 4,
            ],
            [
                'course_code' => '21IF1004',
                'course_name' => 'Struktur Data dan Algoritma',
                'description' => 'Mempelajari berbagai struktur data seperti array, linked list, stack, queue, tree, dan graph. Mahasiswa akan memahami bagaimana memilih struktur data yang tepat untuk menyelesaikan masalah tertentu. Selain itu, kursus ini juga mencakup algoritma dasar seperti pencarian, pengurutan, dan algoritma rekursif. Fokus utama adalah pada analisis kompleksitas waktu dan ruang dari algoritma yang dipelajari.',
                'credits' => 4,
            ],
            [
                'course_code' => '25IF2114',
                'course_name' => 'Basis Data',
                'description' => 'Mempelajari konsep dasar basis data, termasuk model relasional, SQL, dan desain basis data. Mahasiswa akan belajar cara membuat, mengelola, dan memanipulasi basis data menggunakan SQL. Kursus ini juga mencakup topik seperti normalisasi, transaksi, dan keamanan basis data. Fokus utama adalah pada pemahaman bagaimana basis data digunakan dalam aplikasi dunia nyata dan bagaimana merancang basis data yang efisien dan efektif.',
                'credits' => 4,
            ],
            [
                'course_code' => '25IF2113',
                'course_name' => 'Pemrograman Berorientasi Objek',
                'description' => 'Mempelajari paradigma pemrograman berorientasi objek (OOP) yang mencakup konsep seperti kelas, objek, pewarisan, polimorfisme, dan enkapsulasi. Mahasiswa akan belajar menulis kode menggunakan bahasa pemrograman berorientasi objek seperti Java. Kursus ini juga mencakup prinsip desain perangkat lunak dan pola desain yang umum digunakan dalam pengembangan perangkat lunak. Fokus utama adalah pada penerapan OOP untuk membangun aplikasi yang modular, dapat dipelihara, dan dapat diperluas.',
                'credits' => 3,
            ],
            [
                'course_code' => '25IF2115',
                'course_name' => 'Komputer Grafik',
                'description' => 'Mempelajari dasar-dasar komputer grafik, termasuk representasi grafis, algoritma rendering, dan pemodelan 3D. Mahasiswa akan belajar menggunakan perangkat lunak grafis seperti OpenGL atau DirectX untuk membuat dan memanipulasi gambar dan animasi. Kursus ini juga mencakup topik seperti transformasi geometris, pencahayaan, dan tekstur. Fokus utama adalah pada pemahaman bagaimana komputer menghasilkan gambar dan bagaimana menerapkan teknik grafis dalam aplikasi interaktif seperti game dan simulasi.',
                'credits' => 2,
            ],
        ];

        foreach ($courses as $course) {
            $courseModel->insert($course);
        }
    }
}

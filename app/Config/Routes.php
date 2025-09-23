<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

service('auth')->routes($routes,  ['except' => ['login', 'logout']]);

$routes->get('login', 'LoginController::loginView');
$routes->post('login', 'LoginController::loginAction');
$routes->get('logout', 'LoginController::logoutAction');

$routes->get('/', 'Home::index', ['filter' => 'cookiejwt']);

$routes->group('admin', ['filter' => ['cookiejwt', 'group:admin']], static function (RouteCollection $routes) {
    // Dashboard Admin
    $routes->get('/', 'Admin\DashboardController::index');
    $routes->get('dashboard', 'Admin\DashboardController::index');

    // --- Kelola Mata Kuliah / Courses ---
    $routes->group('courses', static function (RouteCollection $routes) {
        $routes->get('/', 'Admin\CourseController::index'); // Menampilkan semua mata kuliah
        $routes->get('new', 'Admin\CourseController::new'); // Menampilkan form tambah
        $routes->get('(:segment)', 'Admin\CourseController::show/$1'); // Menampilkan detail mata kuliah
        $routes->post('create', 'Admin\CourseController::create'); // Memproses form tambah
        $routes->get('edit/(:segment)', 'Admin\CourseController::edit/$1'); // Menampilkan form edit
        $routes->put('update/(:segment)', 'Admin\CourseController::update/$1'); // Memproses form edit
        $routes->delete('delete/(:segment)', 'Admin\CourseController::delete/$1'); // Menghapus data
        $routes->delete('bulk-delete', 'Admin\CourseController::bulkDelete'); // Menghapus data massal
    });

    // --- Kelola User (student dan admin) ---
    $routes->group('users', static function (RouteCollection $routes) {
        $routes->get('/', 'Admin\UserController::index'); // Menampilkan semua user
        $routes->get('(:num)', 'Admin\UserController::show/$1'); // Menampilkan detail user
        $routes->get('new', 'Admin\UserController::new'); // Menampilkan form tambah user
        $routes->post('create', 'Admin\UserController::create'); // Memproses form tambah
        $routes->get('edit/(:num)', 'Admin\UserController::edit/$1'); // Menampilkan form edit
        $routes->put('update/(:num)', 'Admin\UserController::update/$1'); // Memproses form edit
        $routes->delete('delete/(:num)', 'Admin\UserController::delete/$1'); // Menghapus user
    });
});

$routes->group('student', ['filter' => ['cookiejwt', 'group:student']], static function (RouteCollection $routes) {
    // Dashboard Student
    $routes->get('/', 'Student\DashboardController::index');
    $routes->get('dashboard', 'Student\DashboardController::index');

    // --- Student Courses ---
    $routes->group('courses', static function (RouteCollection $routes) {
        $routes->get('/', 'Student\CourseController::index'); // Menampilkan daftar semua mata kuliah yang bisa diambil

        $routes->get('my', 'Student\CourseController::myCourses'); // Menampilkan daftar mata kuliah yang sudah diambil oleh student

        $routes->get('(:segment)', 'Student\CourseController::show/$1'); // Menampilkan detail mata kuliah

        // Memproses pendaftaran (enroll) ke beberapa mata kuliah sekaligus
        $routes->post('bulk-enroll', 'Student\CourseController::bulkEnroll');

        // Memproses pembatalan pendaftaran (unenroll) ke beberapa mata kuliah sekaligus
        $routes->post('bulk-unenroll', 'Student\CourseController::bulkUnEnroll');

        // Memproses pendaftaran (enroll) ke sebuah mata kuliah
        $routes->post('enroll/(:segment)', 'Student\CourseController::enroll/$1');

        // Memproses pembatalan pendaftaran (unenroll) ke sebuah mata kuliah
        $routes->post('unenroll/(:segment)', 'Student\CourseController::unEnroll/$1');
    });
});

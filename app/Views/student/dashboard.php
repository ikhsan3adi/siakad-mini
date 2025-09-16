<?= $this->extend('templates/main_layout') ?>

<?= $this->section('title') ?>Dashboard - SIAKAD<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="container mt-4">
    <h1 class="mb-4">Dashboard</h1>
    <div class="row row-gap-3">
        <div class="col-sm-6 col-md-4">
            <a href="<?= base_url('student/courses/my') ?>" class="text-decoration-none">
                <div class="card text-white bg-primary mb-3 h-100">
                    <div class="card-body">
                        <h5 class="card-title">Total Enrolled Courses</h5>
                        <p class="card-text"><?= $totalEnrolledCourses ?></p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-sm-6 col-md-4">
            <a href="<?= base_url('student/courses/my?completed=true') ?>" class="text-decoration-none">
                <div class="card text-white <?= $totalCompletedCourses == 0 ? 'bg-secondary' : 'bg-success' ?> mb-3 h-100">
                    <div class="card-body">
                        <h5 class="card-title">Total Completed Courses</h5>
                        <p class="card-text"><?= $totalCompletedCourses ?></p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-sm-6 col-md-4">
            <a href="<?= base_url('student/courses/my?completed=true') ?>" class="text-decoration-none">
                <div class="card text-white bg-info mb-3 h-100">
                    <div class="card-body">
                        <h5 class="card-title text-dark">Total Credits Earned</h5>
                        <p class="card-text text-dark"><?= $totalCredits ?></p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-sm-6 col-md-4">
            <?php
            $bgClass = 'bg-warning';
            $textClass = '';
            if (is_numeric($averageGrade)) {
                if ($averageGrade >= 3.5) {
                    $bgClass = 'bg-success';
                } elseif ($averageGrade >= 2.0) {
                    $bgClass = 'bg-info';
                    $textClass = 'text-dark';
                } elseif ($averageGrade > 0) {
                    $bgClass = 'bg-warning';
                    $textClass = 'text-dark';
                } else {
                    $bgClass = 'bg-danger';
                }
            } else {
                $bgClass = 'bg-secondary';
            }
            ?>
            <a href="<?= base_url('student/courses/my?completed=true') ?>" class="text-decoration-none">
                <div class="card text-white <?= $bgClass ?> mb-3 h-100">
                    <div class="card-body">
                        <h5 class="card-title <?= $textClass ?>">Average Grade</h5>
                        <p class="card-text <?= $textClass ?>"><?= $averageGrade ?></p>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-sm-6 col-md-4">
            <a href="<?= base_url('student/courses') ?>" class="text-decoration-none">
                <div class="card text-white bg-warning mb-3 h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between text-dark">
                            <h5 class="card-title">Browse Available Courses</h5>
                            <i class="bi bi-arrow-return-left" style="font-size: 24px;"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
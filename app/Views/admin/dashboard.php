<?= $this->extend('templates/main_layout') ?>

<?= $this->section('title') ?>Dashboard - SIAKAD<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="container mt-4">
    <h1 class="mb-4">Dashboard</h1>
    <div class="row row-gap-3">
        <div class="col-sm-6 col-md-4">
            <a href="<?= base_url('admin/users?type=student') ?>" class="text-decoration-none">
                <div class="card text-white bg-primary mb-3 h-100">
                    <div class="card-body">
                        <h5 class="card-title">Total Students</h5>
                        <p class="card-text"><?= $total_students ?></p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-sm-6 col-md-4">
            <a href="<?= base_url('admin/courses') ?>" class="text-decoration-none">
                <div class="card text-white bg-success mb-3 h-100">
                    <div class="card-body">
                        <h5 class="card-title">Total Courses</h5>
                        <p class="card-text"><?= $total_courses ?></p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-12 col-md-4">
            <a href="<?= base_url('admin/users?type=admin') ?>" class="text-decoration-none">
                <div class="card text-white bg-danger mb-3 h-100">
                    <div class="card-body">
                        <h5 class="card-title">Total Admin</h5>
                        <p class="card-text"><?= $total_admins ?></p>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
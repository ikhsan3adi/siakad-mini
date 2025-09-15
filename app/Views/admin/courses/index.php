<?= $this->extend('templates/main_layout') ?>

<?= $this->section('title') ?>
Courses Management - SIAKAD
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="container mt-4">

    <div class="row">
        <div class="col">
            <h1 class="mb-4">Courses Management</h1>
            <div class="row">
                <div class="col-12 col-md-6">
                    <a href="<?= base_url('/admin/courses/new') ?>" class="btn btn-primary mb-3"><i class="bi bi-plus"></i> Add Course</a>
                </div>
                <div class="col-12 col-md-6">
                    <form action="<?= base_url('/admin/courses') ?>" method="get" class="d-flex" role="search">
                        <div class="input-group mb-3">
                            <input class="form-control" type="search" name="keyword" placeholder="Search course" aria-label="Search" value="<?= esc(request()->getVar('keyword') ?? '') ?>">
                            <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i> Search</button>
                        </div>
                    </form>
                </div>
            </div>


            <?php if (session()->getFlashdata('message')) : ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= session()->getFlashdata('message') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            <?php if (session()->getFlashdata('error')) : ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= session()->getFlashdata('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>


            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Course Code</th>
                        <th>Course Name</th>
                        <th>Credits</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $num = 1 + ($perPage * ($currentPage - 1)); ?>
                    <?php foreach ($courses as $course) : ?>
                        <tr>
                            <td><?= $num++ ?></td>
                            <td><?= esc($course['course_code']) ?></td>
                            <td><?= esc($course['course_name']) ?></td>
                            <td><?= esc($course['credits']) ?></td>
                            <td>
                                <a href="<?= base_url('/admin/courses/' . $course['course_code']) ?>" class="btn btn-info btn-sm">
                                    <i class="bi bi-eye"></i> Detail
                                </a>
                                <a href="<?= base_url('/admin/courses/edit/' . $course['course_code']) ?>" class="btn btn-warning btn-sm">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                <!-- <form action="<?= base_url('/admin/courses/delete/' . $course['course_code']) ?>" method="post" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this course?');">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>g
                                </form> -->
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($courses)) : ?>
                        <tr>
                            <td colspan="4" class="text-center">No courses found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <?= $pager->links('courses', 'my_pager'); ?>
        </div>
    </div>

</div>

<?= $this->endSection() ?>
<?= $this->extend('templates/main_layout') ?>

<?= $this->section('title') ?>
Courses - SIAKAD
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="container mt-4">
    <div class="row">
        <div class="col">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                <h1 class="mb-4">Courses</h1>

                <a href="<?= base_url('student/courses/my') ?>" class="btn btn-outline-info mb-3">
                    <i class="bi bi-arrow-left-right"></i> Show My Enrolled Courses
                </a>
            </div>

            <div class="row">
                <div class="col-12 col-md-6">
                    <form action="<?= base_url('/student/courses') ?>" method="get" class="d-flex" role="search">
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
                            <td class="d-flex gap-2">
                                <a href="<?= base_url('/student/courses/' . $course['course_code']) ?>" class="btn btn-info btn-sm">
                                    <i class="bi bi-eye"></i> Detail
                                </a>
                                <?php if ($course['enrolled']) : ?>
                                    <span class="badge bg-success fs-6 fw-normal">Enrolled <i class="bi bi-check"></i></span>
                                <?php else : ?>
                                    <form action="<?= base_url('/student/courses/enroll/' . $course['course_code']) ?>" method="post" class="d-inline" onsubmit="return confirm('Are you sure you want to enroll in this course?');">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-primary btn-sm">
                                            <i class="bi bi-journal-plus"></i> Enroll
                                        </button>
                                    </form>
                                <?php endif; ?>
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
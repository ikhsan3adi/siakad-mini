<?= $this->extend('templates/main_layout') ?>

<?= $this->section('title') ?>User Details - SIAKAD<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="container mt-4">
    <div class="row">
        <div class="col">
            <h1 class="mb-4">User Details</h1>

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

            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title"><?= esc($user['full_name']) ?></h5>
                    <p class="card-text"><strong>Username:</strong> <?= esc($user['username']) ?></p>
                    <p class="card-text"><strong>Email:</strong> <?= esc($user['email']) ?></p>
                    <?php if ($user['entry_year']) : ?>
                        <p class="card-text"><strong>Entry Year:</strong> <?= esc($user['entry_year']) ?></p>
                    <?php endif; ?>
                    <a href="<?= base_url('/admin/users' . (in_array('admin', $user['groups']) ? '?type=admin' : '?type=student')) ?>" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Back to Users
                    </a>
                    <a href="<?= base_url('/admin/users/edit/' . esc($user['id'])) ?>" class="btn btn-warning">
                        <i class="bi bi-pencil"></i> Edit User
                    </a>
                    <!-- delete button (only student) -->
                    <?php if (in_array('student', $user['groups'])) : ?>
                        <form action="<?= base_url('/admin/users/delete/' . esc($user['id'])) ?>" method="post" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this user?');">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn btn-danger"><i class="bi bi-trash"></i> Delete User</button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>

            <h2 class="mb-3">Enrolled Courses</h2>
            <?php if (empty($enrolledCourses)) : ?>
                <p>This user is not enrolled in any courses.</p>
            <?php else : ?>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Course Code</th>
                            <th>Course Name</th>
                            <th>Credits</th>
                            <th>Grade</th>
                            <th>Enroll Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $num = 1; ?>
                        <?php foreach ($enrolledCourses as $course) : ?>
                            <tr>
                                <td><?= $num++ ?></td>
                                <td><?= esc($course['course_code']) ?></td>
                                <td><?= esc($course['course_name']) ?></td>
                                <td><?= esc($course['credits']) ?></td>
                                <td>
                                    <?php if (is_null($course['grade'])): ?>
                                        <span class="badge py-1 px-2 fs-6 bg-secondary">N/A</span>
                                    <?php elseif ($course['grade'] >= 3.5): ?>
                                        <span class="badge py-1 px-2 fs-6 bg-success"><?= esc($course['grade']) ?></span>
                                    <?php elseif ($course['grade'] >= 2.5): ?>
                                        <span class="badge py-1 px-2 fs-6 bg-white text-dark"><?= esc($course['grade']) ?></span>
                                    <?php else: ?>
                                        <span class="badge py-1 px-2 fs-6 bg-danger"><?= esc($course['grade']) ?></span>
                                    <?php endif; ?>
                                </td>
                                <td><?= date('d M Y', strtotime($course['enroll_date'])) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
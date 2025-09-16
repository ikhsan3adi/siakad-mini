<?= $this->extend('templates/main_layout') ?>

<?= $this->section('title') ?>
<?= $course['course_name'] ?> - Course Details - SIAKAD
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="container mt-4">
    <div class="row">
        <div class="col">
            <h1 class="mb-4">Course Details</h1>

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
                    <h5 class="card-title"><?= esc($course['course_name']) ?> (<?= esc($course['course_code']) ?>)</h5>
                    <p class="card-text"><strong>Credits:</strong> <?= esc($course['credits']) ?></p>
                    <p class="card-text"><strong>Description:</strong> <?= esc($course['description'] ?? 'N/A') ?></p>
                    <a href="<?= base_url('/admin/courses') ?>" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Back to Courses</a>
                    <a href="<?= base_url('/admin/courses/edit/' . esc($course['course_code'])) ?>" class="btn btn-warning"><i class="bi bi-pencil"></i> Edit Course</a>
                </div>
            </div>
            <h2 class="mb-3">Enrolled Students</h2>
            <?php if (empty($enrolledStudents)) : ?>
                <p>No students are currently enrolled in this course.</p>
            <?php else : ?>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Student ID</th>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Entry Year</th>
                            <th>Grade</th>
                            <th>Enroll Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $num = 1; ?>
                        <?php foreach ($enrolledStudents as $student) : ?>
                            <tr>
                                <td><?= $num++ ?></td>
                                <td><?= esc($student['username']) ?></td>
                                <td><?= esc($student['full_name']) ?></td>
                                <td><?= esc($student['email']) ?></td>
                                <td><?= esc($student['entry_year']) ?></td>
                                <td>
                                    <?php if (is_null($student['grade'])): ?>
                                        <span class="badge py-1 px-2 fs-6 bg-secondary">N/A</span>
                                    <?php elseif ($student['grade'] >= 3.5): ?>
                                        <span class="badge py-1 px-2 fs-6 bg-success"><?= esc($student['grade']) ?></span>
                                    <?php elseif ($student['grade'] >= 2.5): ?>
                                        <span class="badge py-1 px-2 fs-6 bg-white text-dark"><?= esc($student['grade']) ?></span>
                                    <?php else: ?>
                                        <span class="badge py-1 px-2 fs-6 bg-danger"><?= esc($student['grade']) ?></span>
                                    <?php endif; ?>
                                </td>
                                <td><?= date('d M Y', strtotime($student['enroll_date'])) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
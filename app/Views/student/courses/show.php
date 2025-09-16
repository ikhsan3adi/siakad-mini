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
                    <div class="d-flex justify-content-between align-content-center">
                        <h5 class="card-title"><?= esc($course['course_name']) ?> (<?= esc($course['course_code']) ?>)</h5>
                        <?php if ($enrolled) : ?>
                            <span class="badge bg-success fs-6">Enrolled <i class="bi bi-check"></i></span>
                        <?php else : ?>

                        <?php endif; ?>
                    </div>
                    <p class="card-text"><strong>Credits:</strong> <?= esc($course['credits']) ?></p>
                    <p class="card-text"><strong>Description:</strong> <?= esc($course['description'] ?? 'N/A') ?></p>


                    <!-- if enrolled, show grade & enroll_date -->
                    <?php if ($enrolled) : ?>
                        <p class="card-text">
                            <strong>Enroll Date:</strong> <?= date('d M Y', strtotime($course['enroll_date'])) ?>
                        </p>
                        <p class="card-text"><strong>Grade:</strong>
                            <?php if (is_null($course['grade'])): ?>
                                <span class="badge py-1 px-2 fs-6 bg-secondary">N/A</span>
                            <?php elseif ($course['grade'] >= 3.5): ?>
                                <span class="badge py-1 px-2 fs-6 bg-success"><?= esc($course['grade']) ?></span>
                            <?php elseif ($course['grade'] >= 2.5): ?>
                                <span class="badge py-1 px-2 fs-6 bg-white text-dark"><?= esc($course['grade']) ?></span>
                            <?php else: ?>
                                <span class="badge py-1 px-2 fs-6 bg-danger"><?= esc($course['grade']) ?></span>
                            <?php endif; ?>
                        </p>
                    <?php endif; ?>


                    <a href="<?= $previousUrl ?>" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Back to Courses</a>

                    <?php if (!$enrolled) : ?>
                        <form action="<?= base_url('/student/courses/enroll/' . $course['course_code']) ?>" method="post" class="d-inline">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn btn-primary"><i class="bi bi-journal-plus"></i> Enroll</button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
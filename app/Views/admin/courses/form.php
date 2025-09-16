<?= $this->extend('templates/main_layout') ?>

<?= $this->section('title') ?>
<?= isset($course) ? 'Edit Course - SIAKAD' : 'New Course - SIAKAD' ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="container mt-4">
    <div class="row">
        <div class="col">
            <h1 class="mb-4"><?= isset($course) ? 'Edit Course' : 'New Course' ?></h1>

            <?php if (session()->getFlashdata('message')) : ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= session()->getFlashdata('message') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            <?php if (session()->getFlashdata('error')) : ?>
                <div class="alert alert-danger" role="alert"><?= session()->getFlashdata('error') ?></div>
            <?php elseif (session()->getFlashdata('errors')) : ?>
                <div class="alert alert-danger" role="alert">
                    <?php if (is_array(session()->getFlashdata('errors'))) : ?>
                        <?php foreach (session()->getFlashdata('errors') as $error) : ?>
                            <?= esc($error) ?>
                            <br>
                        <?php endforeach ?>
                    <?php else : ?>
                        <?= session()->getFlashdata('errors') ?>
                    <?php endif ?>
                </div>
            <?php endif ?>


            <form action="<?= isset($course) ? base_url('/admin/courses/update/' . esc($course['course_code'])) : base_url('/admin/courses/create') ?>" method="post">
                <?= csrf_field() ?>

                <?php if (isset($course)) : ?>
                    <input type="hidden" name="_method" value="PUT">
                <?php endif; ?>

                <div class="mb-3">
                    <label for="course_name" class="form-label">Course Name</label>
                    <input type="text" class="form-control" id="course_name" name="course_name" value="<?= isset($course) ? esc($course['course_name']) : old('course_name') ?>" maxlength="100" required>
                </div>

                <div class="row mb-3">
                    <div class="col">
                        <label for="course_code" class="form-label">Course Code</label>
                        <input type="text" class="form-control" id="course_code" name="course_code" value="<?= isset($course) ? esc($course['course_code']) : old('course_code') ?>" maxlength="10" required <?= isset($course) ? 'readonly' : '' ?>>
                    </div>
                    <div class="col">
                        <label for="credits" class="form-label">Credits</label>
                        <input type="number" class="form-control" id="credits" name="credits" value="<?= isset($course) ? esc($course['credits']) : old('credits') ?>" min="1" max="9" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3"><?= isset($course) ? esc($course['description']) : old('description') ?></textarea>
                </div>
                <button type="submit" class="btn btn-primary"><?= isset($course) ? 'Update Course' : 'Create Course' ?></button>
                <a href="<?= base_url('/admin/courses') ?>" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
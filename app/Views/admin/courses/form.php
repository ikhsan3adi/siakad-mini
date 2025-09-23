<?= $this->extend('templates/main_layout') ?>

<?= $this->section('title') ?>
<?= isset($course) ? 'Edit Course - SIAKAD' : 'New Course - SIAKAD' ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="container mt-4">
    <div class="row">
        <div class="col">
            <h1 class="mb-4"><?= isset($course) ? 'Edit Course' : 'New Course' ?></h1>

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


            <form action="<?= isset($course) ? base_url('/admin/courses/update/' . esc($course['course_code'])) : base_url('/admin/courses/create') ?>" method="post" class="needs-validation" novalidate>
                <?= csrf_field() ?>

                <?php if (isset($course)) : ?>
                    <input type="hidden" name="_method" value="PUT">
                <?php endif; ?>

                <div class="mb-3">
                    <label for="course_name" class="form-label">Course Name</label>
                    <input type="text" class="form-control" id="course_name" name="course_name" value="<?= isset($course) ? esc($course['course_name']) : old('course_name') ?>" maxlength="100" required>
                    <div class="invalid-feedback"></div>
                </div>

                <div class="row mb-3">
                    <div class="col">
                        <label for="course_code" class="form-label">Course Code</label>
                        <input type="text" class="form-control" id="course_code" name="course_code" value="<?= isset($course) ? esc($course['course_code']) : old('course_code') ?>" maxlength="10" required <?= isset($course) ? 'readonly' : '' ?>>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="col">
                        <label for="credits" class="form-label">Credits</label>
                        <input type="number" class="form-control" id="credits" name="credits" value="<?= isset($course) ? esc($course['credits']) : old('credits') ?>" min="1" max="9" required>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3" required><?= isset($course) ? esc($course['description']) : old('description') ?></textarea>
                    <div class="invalid-feedback"></div>
                </div>

                <button id="submitButton" type="submit" class="btn btn-primary"><?= isset($course) ? 'Update Course' : 'Create Course' ?></button>
                <a href="<?= base_url('/admin/courses') ?>" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    var courseNameInput = document.getElementById("course_name");
    var courseCodeInput = document.getElementById("course_code");
    var creditsInput = document.getElementById("credits");
    var descriptionInput = document.getElementById("description");
    var submitButton = document.getElementById("submitButton");

    courseNameInput.addEventListener("input", validateCourseName);
    courseCodeInput.addEventListener("input", validateCourseCode);
    creditsInput.addEventListener("input", validateCredits);
    descriptionInput.addEventListener("input", validateDescription);

    function validateCourseName() {
        let value = courseNameInput.value.trim();
        let feedback = courseNameInput.nextElementSibling;

        if (value.length === 0) {
            courseNameInput.classList.add("is-invalid");
            feedback.textContent = "Course name is required.";
            return false;
        } else if (value.length < 3) {
            courseNameInput.classList.add("is-invalid");
            feedback.textContent = "Course name must be at least 3 characters.";
            return false;
        } else if (value.length > 100) {
            courseNameInput.classList.add("is-invalid");
            feedback.textContent = "Course name must be at most 100 characters.";
            return false;
        }

        courseNameInput.classList.remove("is-invalid");
        feedback.textContent = "";
        return true;
    }

    function validateCourseCode() {
        let value = courseCodeInput.value.trim();
        let feedback = courseCodeInput.nextElementSibling;

        if (value.length === 0) {
            courseCodeInput.classList.add("is-invalid");
            feedback.textContent = "Course code is required.";
            return false;
        } else if (value.length > 10) {
            courseCodeInput.classList.add("is-invalid");
            feedback.textContent = "Course code must be at most 10 characters.";
            return false;
        }

        courseCodeInput.classList.remove("is-invalid");
        feedback.textContent = "";
        return true;
    }

    function validateCredits() {
        let value = parseInt(creditsInput.value);
        let feedback = creditsInput.nextElementSibling;

        if (isNaN(value)) {
            creditsInput.classList.add("is-invalid");
            feedback.textContent = "Credits must be a number.";
            return false;
        } else if (value < 1 || value > 9) {
            creditsInput.classList.add("is-invalid");
            feedback.textContent = "Credits must be between 1 and 9.";
            return false;
        }

        creditsInput.classList.remove("is-invalid");
        feedback.textContent = "";
        return true;
    }

    function validateDescription() {
        let value = descriptionInput.value.trim();
        let feedback = descriptionInput.nextElementSibling;

        if (value.length === 0) {
            descriptionInput.classList.add("is-invalid");
            feedback.textContent = "Description is required.";
            return false;
        }

        descriptionInput.classList.remove("is-invalid");
        feedback.textContent = "";
        return true;
    }

    let validations = [validateCourseName, validateCourseCode, validateCredits, validateDescription];

    (function() {
        var forms = document.querySelectorAll('.needs-validation');

        Array.prototype.slice.call(forms)
            .forEach(function(form) {
                form.addEventListener('submit', function(event) {
                    if (!validations.every(fn => fn())) {
                        event.preventDefault();
                        event.stopPropagation();
                    } else {
                        submitButton.disabled = true;
                        submitButton.innerHTML = 'Submitting...';
                    }
                }, false);
            });
    })();
</script>
<?= $this->endSection() ?>
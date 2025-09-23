<?= $this->extend('templates/main_layout') ?>

<?= $this->section('title') ?>
<?= isset($course) ? 'Edit User - SIAKAD' : 'New User - SIAKAD' ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="container mt-4">
    <div class="row">
        <div class="col">
            <h1 class="mb-4"><?= isset($user) ? 'Edit User' : 'New User' ?></h1>

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

            <form action="<?= isset($user) ? base_url('/admin/users/update/' . esc($user['id'])) : base_url('/admin/users/create') ?>" method="post" class="needs-validation" novalidate>
                <?= csrf_field() ?>
                <?php if (isset($user)) : ?>
                    <input type="hidden" name="_method" value="PUT">
                <?php endif; ?>


                <div class="mb-3"><label for="full_name" class="form-label">Full Name</label>
                    <input type="text" class="form-control" id="full_name" name="full_name" value="<?= isset($user) ? esc($user['full_name']) : old('full_name') ?>" maxlength="100" required>
                    <div class="invalid-feedback"></div>
                </div>

                <div class="row mb-3">
                    <div class="col">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?= isset($user) ? esc($user['email']) : old('email') ?>" maxlength="255" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="col">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" value="<?= isset($user) ? esc($user['username']) : old('username') ?>" minlength="3" maxlength="50" required>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password <?= isset($user) ? '(leave blank to keep current password)' : '' ?></label>
                    <input type="password" class="form-control" id="password" name="password" maxlength="255" <?= isset($user) ? '' : 'required' ?>>
                    <div class="invalid-feedback"></div>
                </div>

                <?php if (!isset($user)) : ?>
                    <?php if ($userType === 'student'): ?>
                        <div class="mb-3">
                            <label for="entry_year" class="form-label">Entry Year</label>
                            <input type="number" class="form-control" id="entry_year" name="entry_year" value="<?= old('entry_year') ?? date('Y') ?>" min="1900" max="<?= date('Y') ?>" required>
                            <div class="invalid-feedback"></div>
                        </div>
                    <?php endif; ?>

                    <div class="mb-3">
                        <label for="user_type" class="form-label">User Type</label>
                        <select class="form-select" id="user_type" name="user_type" required>
                            <option value="" disabled <?= old('user_type') ? '' : 'selected' ?>>Select user type</option>
                            <option value="admin" <?= old('user_type') ?? $userType === 'admin' ? 'selected' : '' ?>>Admin</option>
                            <option value="student" <?= old('user_type') ?? $userType === 'student' ? 'selected' : '' ?>>Student</option>
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                <?php else : ?>
                    <?php if (in_array('student', $user['groups'])) : ?>
                        <div class="mb-3">
                            <label for="entry_year" class="form-label">Entry Year (optional)</label>
                            <input type="number" class="form-control" id="entry_year" name="entry_year" value="<?= isset($user) ? esc($user['entry_year']) : old('entry_year') ?>" min="1900" max="<?= date('Y') ?>">
                            <div class="invalid-feedback"></div>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>

                <button type="submit" id="submitButton" class="btn btn-primary">
                    <?= isset($user) ? 'Update User' : 'Create User' ?>
                </button>
                <a href="<?= base_url('/admin/users?type=' . $userType) ?>" class="btn btn-secondary">
                    Cancel
                </a>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>


<?= $this->section('scripts') ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const fullNameInput = document.getElementById("full_name");
        const emailInput = document.getElementById("email");
        const usernameInput = document.getElementById("username");
        const passwordInput = document.getElementById("password");
        const entryYearInput = document.getElementById("entry_year");
        const submitButton = document.getElementById("submitButton");

        const forms = document.querySelectorAll('.needs-validation');

        let validations = [];

        if (fullNameInput) {
            fullNameInput.addEventListener("input", validateFullName);
            validations.push(validateFullName);
        }
        if (emailInput) {
            emailInput.addEventListener("input", validateEmail);
            validations.push(validateEmail);
        }
        if (usernameInput) {
            usernameInput.addEventListener("input", validateUsername);
            validations.push(validateUsername);
        }
        if (passwordInput) {
            passwordInput.addEventListener("input", validatePassword);
            validations.push(validatePassword);
        }
        if (entryYearInput) {
            entryYearInput.addEventListener("input", validateEntryYear);
            validations.push(validateEntryYear);
        }


        function validateFullName() {
            let value = fullNameInput.value.trim();
            let feedback = fullNameInput.nextElementSibling;

            if (value.length === 0) {
                fullNameInput.classList.add("is-invalid");
                feedback.textContent = "Full name is required.";
                return false;
            } else if (value.length < 3) {
                fullNameInput.classList.add("is-invalid");
                feedback.textContent = "Full name must be at least 3 characters.";
                return false;
            } else if (value.length > 100) {
                fullNameInput.classList.add("is-invalid");
                feedback.textContent = "Full name must be at most 100 characters.";
                return false;
            }

            fullNameInput.classList.remove("is-invalid");
            feedback.textContent = "";
            return true;
        }

        function validateEmail() {
            let value = emailInput.value.trim();
            let feedback = emailInput.nextElementSibling;
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (value.length === 0) {
                emailInput.classList.add("is-invalid");
                feedback.textContent = "Email is required.";
                return false;
            } else if (!emailRegex.test(value)) {
                emailInput.classList.add("is-invalid");
                feedback.textContent = "Please enter a valid email address.";
                return false;
            } else if (value.length > 255) {
                emailInput.classList.add("is-invalid");
                feedback.textContent = "Email must be at most 255 characters.";
                return false;
            }

            emailInput.classList.remove("is-invalid");
            feedback.textContent = "";
            return true;
        }

        function validateUsername() {
            let value = usernameInput.value.trim();
            let feedback = usernameInput.nextElementSibling;

            if (value.length === 0) {
                usernameInput.classList.add("is-invalid");
                feedback.textContent = "Username is required.";
                return false;
            } else if (value.length < 3) {
                usernameInput.classList.add("is-invalid");
                feedback.textContent = "Username must be at least 3 characters.";
                return false;
            } else if (value.length > 50) {
                usernameInput.classList.add("is-invalid");
                feedback.textContent = "Username must be at most 50 characters.";
                return false;
            }

            usernameInput.classList.remove("is-invalid");
            feedback.textContent = "";
            return true;
        }

        function validatePassword() {
            let value = passwordInput.value;
            let feedback = passwordInput.nextElementSibling;

            if (passwordInput.hasAttribute('required') && value.length === 0) {
                passwordInput.classList.add("is-invalid");
                feedback.textContent = "Password is required.";
                return false;
            }

            if (value.length > 0 && value.length < 8) {
                passwordInput.classList.add("is-invalid");
                feedback.textContent = "Password must be at least 8 characters.";
                return false;
            }

            if (value.length > 255) {
                passwordInput.classList.add("is-invalid");
                feedback.textContent = "Password must be at most 255 characters.";
                return false;
            }

            passwordInput.classList.remove("is-invalid");
            feedback.textContent = "";
            return true;
        }

        function validateEntryYear() {
            let value = parseInt(entryYearInput.value);
            let feedback = entryYearInput.nextElementSibling;
            const currentYear = new Date().getFullYear();

            if (isNaN(value)) {
                entryYearInput.classList.add("is-invalid");
                feedback.textContent = "Entry year must be a number.";
                return false;
            } else if (value < 1900 || value > currentYear) {
                entryYearInput.classList.add("is-invalid");
                feedback.textContent = `Entry year must be between 1900 and ${currentYear}.`;
                return false;
            }

            entryYearInput.classList.remove("is-invalid");
            feedback.textContent = "";
            return true;
        }

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
    });
</script>
<?= $this->endSection() ?>
<?= $this->extend(config('Auth')->views['layout']) ?>

<?= $this->section('title') ?><?= lang('Auth.login') ?> <?= $this->endSection() ?>

<?= $this->section('main') ?>
<div class="container p-5 min-vh-100 d-flex flex-column justify-content-center align-items-center">
    <div class="card col-12 col-md-5 shadow-sm">
        <div class="card-body">
            <h4 class="card-title text-center mb-4">
                <i class="bi bi-mortarboard-fill pe-none me-2" style="font-size: 24pt;"></i>
                SIAKAD
            </h4>
            <h5 class="card-title mb-3"><?= lang('Auth.login') ?></h5>

            <?php if (session('error') !== null) : ?>
                <div class="alert alert-danger" role="alert"><?= esc(session('error')) ?></div>
            <?php elseif (session('errors') !== null) : ?>
                <div class="alert alert-danger" role="alert">
                    <?php if (is_array(session('errors'))) : ?>
                        <?php foreach (session('errors') as $error) : ?>
                            <?= esc($error) ?>
                            <br>
                        <?php endforeach ?>
                    <?php else : ?>
                        <?= esc(session('errors')) ?>
                    <?php endif ?>
                </div>
            <?php endif ?>

            <?php if (session('message') !== null) : ?>
                <div class="alert alert-success" role="alert"><?= esc(session('message')) ?></div>
            <?php endif ?>

            <form action="<?= url_to('login') ?>" method="post" class="needs-validation" novalidate>
                <?= csrf_field() ?>

                <!-- Email -->
                <?php if (in_array('email', setting('Auth.validFields'))): ?>
                    <div class="form-floating mb-3">
                        <input type="email" class="form-control" id="floatingEmailInput" name="email" inputmode="email" autocomplete="email" placeholder="<?= lang('Auth.email') ?>" value="<?= old('email') ?>" required>
                        <label for="floatingEmailInput"><?= lang('Auth.email') ?></label>
                    </div>
                <?php endif ?>

                <!-- Username -->
                <?php if (in_array('username', setting('Auth.validFields'))): ?>
                    <div class="form-floating mb-4">
                        <input type="text" class="form-control" id="floatingUsernameInput" name="username" inputmode="text" autocomplete="username" placeholder="<?= lang('Auth.username') ?>" value="<?= old('username') ?>" minlength="3" required>
                        <label for="floatingUsernameInput"><?= lang('Auth.username') ?></label>
                        <div class="invalid-feedback">
                            Please provide a valid username (at least 3 characters).
                        </div>
                    </div>
                <?php endif ?>

                <!-- Password -->
                <div class="form-floating mb-3">
                    <input type="password" class="form-control" id="floatingPasswordInput" name="password" inputmode="text" autocomplete="current-password" minlength="8" placeholder="<?= lang('Auth.password') ?>" required>
                    <label for="floatingPasswordInput"><?= lang('Auth.password') ?></label>
                    <div class="invalid-feedback">
                        Please provide a valid password (at least 8 characters).
                    </div>
                </div>

                <!-- Remember me -->
                <?php if (setting('Auth.sessionConfig')['allowRemembering']): ?>
                    <div class="form-check">
                        <label class="form-check-label">
                            <input type="checkbox" name="remember" class="form-check-input" <?php if (old('remember')): ?> checked<?php endif ?>>
                            <?= lang('Auth.rememberMe') ?>
                        </label>
                    </div>
                <?php endif; ?>

                <div class="d-grid mx-auto m-3">
                    <button id="submitButton" type="submit" class="btn btn-primary btn-block"><?= lang('Auth.login') ?></button>
                </div>

                <?php if (setting('Auth.allowMagicLinkLogins')) : ?>
                    <p class="text-center"><?= lang('Auth.forgotPassword') ?> <a href="<?= url_to('magic-link') ?>"><?= lang('Auth.useMagicLink') ?></a></p>
                <?php endif ?>

                <?php if (setting('Auth.allowRegistration')) : ?>
                    <p class="text-center"><?= lang('Auth.needAccount') ?> <a href="<?= url_to('register') ?>"><?= lang('Auth.register') ?></a></p>
                <?php endif ?>

            </form>
        </div>
    </div>
</div>

<script>
    var usernameInput = document.getElementById("floatingUsernameInput");
    var passwordInput = document.getElementById("floatingPasswordInput");
    var submitButton = document.getElementById("submitButton");

    usernameInput.addEventListener("input", validateUsername);
    passwordInput.addEventListener("input", validatePassword);

    function validateUsername() {
        // username must be at least 3 characters long
        if (usernameInput.value.length < 3) {
            usernameInput.classList.remove("is-valid");
            usernameInput.classList.add("is-invalid");
            return false;
        }

        usernameInput.classList.remove("is-invalid");
        usernameInput.classList.add("is-valid");
        return true;
    }

    function validatePassword() {
        // password must be at least 8 characters long
        if (passwordInput.value.length < 8) {
            passwordInput.classList.remove("is-valid");
            passwordInput.classList.add("is-invalid");
            return false;
        }

        passwordInput.classList.remove("is-invalid");
        passwordInput.classList.add("is-valid");
        return true;
    }

    let validations = [validateUsername, validatePassword];

    (function() {
        var forms = document.querySelectorAll('.needs-validation');

        Array.prototype.slice.call(forms)
            .forEach(function(form) {
                form.addEventListener('submit', function(event) {
                    if (!form.checkValidity() || !validations.every(fn => fn())) {
                        event.preventDefault();
                        event.stopPropagation();
                    } else {
                        submitButton.disabled = true;
                        submitButton.innerHTML = 'Signing in...';
                    }

                    form.classList.add('was-validated');
                }, false);
            });
    })()
</script>

<?= $this->endSection() ?>
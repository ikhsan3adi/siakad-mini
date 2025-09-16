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

            <form action="<?= isset($user) ? base_url('/admin/users/update/' . esc($user['id'])) : base_url('/admin/users/create') ?>" method="post">
                <?= csrf_field() ?>
                <?php if (isset($user)) : ?>
                    <input type="hidden" name="_method" value="PUT">
                <?php endif; ?>


                <div class="mb-3"><label for="full_name" class="form-label">Full Name</label>
                    <input type="text" class="form-control" id="full_name" name="full_name" value="<?= isset($user) ? esc($user['full_name']) : old('full_name') ?>" maxlength="100" required>

                </div>

                <div class="row mb-3">
                    <div class="col">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?= isset($user) ? esc($user['email']) : old('email') ?>" maxlength="255" required>
                    </div>
                    <div class="col">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" value="<?= isset($user) ? esc($user['username']) : old('username') ?>" maxlength="50" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password <?= isset($user) ? '(leave blank to keep current password)' : '' ?></label>
                    <input type="password" class="form-control" id="password" name="password" maxlength="255" <?= isset($user) ? '' : 'required' ?>>
                </div>

                <?php if (!isset($user)) : ?>
                    <?php if ($userType === 'student'): ?>
                        <div class="mb-3">
                            <label for="entry_year" class="form-label">Entry Year</label>
                            <input type="number" class="form-control" id="entry_year" name="entry_year" value="<?= old('entry_year') ?? date('Y') ?>" min="1900" max="<?= date('Y') ?>" required>
                        </div>
                    <?php endif; ?>

                    <div class="mb-3">
                        <label for="user_type" class="form-label">User Type</label>
                        <select class="form-select" id="user_type" name="user_type" disabled required>
                            <option value="" disabled <?= old('user_type') ? '' : 'selected' ?>>Select user type</option>
                            <option value="admin" <?= old('user_type') ?? $userType === 'admin' ? 'selected' : '' ?>>Admin</option>
                            <option value="student" <?= old('user_type') ?? $userType === 'student' ? 'selected' : '' ?>>Student</option>
                        </select>
                    </div>
                <?php else : ?>
                    <?php if (in_array('student', $user['groups'])) : ?>
                        <div class="mb-3">
                            <label for="entry_year" class="form-label">Entry Year (optional)</label>
                            <input type="number" class="form-control" id="entry_year" name="entry_year" value="<?= isset($user) ? esc($user['entry_year']) : old('entry_year') ?>" min="1900" max="<?= date('Y') ?>">
                        </div>
                    <?php endif; ?>
                <?php endif; ?>

                <button type="submit" class="btn btn-primary">
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
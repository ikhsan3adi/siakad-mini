<?= $this->extend('templates/main_layout') ?>

<?= $this->section('title') ?>
User Management - SIAKAD
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="container mt-4">
    <div class="row">
        <div class="col">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                <h1 class="mb-4"><?= $userType ?? 'Users' ?> Management</h1>

                <a href="<?= base_url('/admin/users?type=' . ($userType === 'Admin' ? 'student' : 'admin')) ?>" class="btn btn-outline-danger mb-3">
                    <i class="bi bi-arrow-left-right"></i> Show <?= $userType === 'Admin' ? 'Students' : 'Admins' ?>
                </a>
            </div>

            <div class="row">
                <div class="col-12 col-md-6">
                    <a href="<?= base_url('/admin/users/new?type=' . (strtolower($userType))) ?>" class="btn btn-primary mb-3">
                        <i class="bi bi-plus"></i> Add <?= $userType ?? 'User' ?>
                    </a>
                </div>
                <div class="col-12 col-md-6">
                    <form action="<?= base_url('/admin/users') ?>" method="get" class="d-flex" role="search">
                        <input type="hidden" name="type" value="<?= strtolower($userType) ?>">
                        <div class="input-group mb-3">
                            <input class="form-control" type="search" name="keyword" placeholder="Search <?= strtolower($userType) ?? 'user' ?>" aria-label="Search" value="<?= esc(request()->getVar('keyword') ?? '') ?>">
                            <button class="btn btn-primary" type="submit">
                                <i class="bi bi-search"></i> Search
                            </button>
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
                        <th>Full Name</th>
                        <th>Username</th>
                        <th>Email</th>
                        <?php if (($userType) === 'Student') : ?>
                            <th>Entry Year</th>
                        <?php endif; ?>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($users)) : ?>
                        <tr>
                            <td colspan="<?= ($userType) === 'Student' ? 6 : 5 ?>" class="text-center">No <?= strtolower($userType) ?? 'user' ?> found.</td>
                        </tr>
                    <?php else : ?>
                        <?php $num = 1; ?>
                        <?php foreach ($users as $user) : ?>
                            <tr>
                                <td><?= $num++ ?></td>
                                <td><?= esc($user['full_name']) ?></td>
                                <td><?= esc($user['username']) ?></td>
                                <td><?= esc($user['email']) ?></td>
                                <?php if (($userType) === 'Student') : ?>
                                    <td><?= esc($user['entry_year']) ?></td>
                                <?php endif; ?>
                                <td>
                                    <a href="<?= base_url('/admin/users/' . $user['id']) ?>" class="btn btn-info btn-sm">
                                        <i class="bi bi-eye"></i> Detail
                                    </a>
                                    <a href="<?= base_url('/admin/users/edit/' . $user['id']) ?>" class="btn btn-warning btn-sm">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>

                                    <?php if (($userType) === 'Student') : ?>
                                        <form action="<?= base_url('/admin/users/delete/' . $user['id']) ?>" method="post" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="_method" value="DELETE">
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="bi bi-trash"></i> Delete
                                            </button>
                                        </form>
                                    <?php endif; ?>

                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>




<?= $this->endSection() ?>
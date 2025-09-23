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

            <?php if (strtolower($userType) !== strtolower('Admin')): ?>
                <div class="row mb-2">
                    <div class="col">
                        <button id="bulkDeleteButton" type="button" class="btn btn-danger mb-3" disabled>
                            <i class="bi bi-trash"></i> Delete Selected
                        </button>
                    </div>
                </div>
            <?php endif; ?>

            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>
                            <label for="selectAllCheckBox" class="d-block w-100 h-100 px-1 py-1 text-center">
                                <input id="selectAllCheckBox" class="form-check-input" type="checkbox" aria-label="Select all user">
                            </label>
                        </th>
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
                            <td colspan="<?= ($userType) === 'Student' ? 7 : 6 ?>" class="text-center">No <?= strtolower($userType) ?? 'user' ?> found.</td>
                        </tr>
                    <?php else : ?>
                        <?php $num = 1; ?>
                        <?php foreach ($users as $user) : ?>
                            <tr>
                                <td class="p-0 m-0">
                                    <?php
                                    $cbId = 'bulk-' . $user['id'];
                                    ?>
                                    <label for="<?= $cbId ?>" class="d-block w-100 h-100 px-1 py-3 text-center">
                                        <input id="<?= $cbId ?>" class="form-check-input" type="checkbox" value="<?= esc($user['id']) ?>" name="selected_user_ids[]" aria-label="Select user">
                                    </label>
                                </td>
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

                                    <button id="singleDeleteButton" type="button" class="btn btn-danger btn-sm"
                                        data-user-id="<?= esc($user['id']) ?>"
                                        data-user-username="<?= esc($user['username']) ?>"
                                        data-user-fullname="<?= esc($user['full_name']) ?>">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>

                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<form id="bulkDeleteForm" action="<?= base_url('admin/users/bulk-delete') ?>" method="post">
    <?= csrf_field() ?>
    <input type="hidden" name="_method" value="DELETE">
</form>

<?= view('templates/modal', [
    'modalId' => 'confirmBulkDeleteModal',
    'modalTitle' => 'Warning',
    'modalBody' => 'Are you sure you want to delete the selected users?',
    'noConfirm' => false,
    'danger' => true,
    'submit' => true,
]) ?>

<?= view('templates/modal', [
    'modalId' => 'noSelectionModal',
    'modalTitle' => 'No Selection',
    'modalBody' => 'Please select at least one user to delete.',
    'noConfirm' => true,
]) ?>

<?= view('templates/modal', [
    'modalId' => 'confirmSingleDeleteModal',
    'noConfirm' => false,
    'danger' => true,
    'submit' => true,
]) ?>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const singleDeleteButtons = document.querySelectorAll('#singleDeleteButton');
        const bulkDeleteButton = document.getElementById('bulkDeleteButton');
        const selectAllCheckBox = document.getElementById('selectAllCheckBox');
        const checkboxes = document.querySelectorAll('input[name="selected_user_ids[]"]');
        const bulkDeleteForm = document.getElementById('bulkDeleteForm');

        const confirmSingleDeleteModal = document.getElementById('confirmSingleDeleteModal');
        const confirmSingleDeleteButton = confirmSingleDeleteModal.querySelector('#modal-confirm');

        const confirmBulkDeleteModal = document.getElementById('confirmBulkDeleteModal');
        const noSelectionModal = document.getElementById('noSelectionModal');

        const BSconfirmSingleDeleteModal = new bootstrap.Modal(confirmSingleDeleteModal);
        const BSconfirmBulkDeleteModal = new bootstrap.Modal(confirmBulkDeleteModal);
        const BSnoSelectionModal = new bootstrap.Modal(noSelectionModal);

        let userToDelete = {
            id: null,
            username: null,
            fullname: null
        };
        singleDeleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                userToDelete = {
                    id: this.getAttribute('data-user-id'),
                    username: this.getAttribute('data-user-username'),
                    fullname: this.getAttribute('data-user-fullname')
                };
                BSconfirmSingleDeleteModal.show();
                confirmSingleDeleteModal.querySelector('.modal-title').textContent =
                    'Delete User "' + userToDelete.username + '"';
                confirmSingleDeleteModal.querySelector('.modal-body').textContent = `Are you sure you want to delete the user "${userToDelete.fullname}"?`;
            });
        });

        confirmSingleDeleteButton.addEventListener('click', function() {
            if (userToDelete.id) {
                const form = document.createElement('form');
                form.action = `/admin/users/delete/${userToDelete.id}`;
                form.method = 'post';

                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '<?= csrf_token() ?>';
                csrfInput.value = '<?= csrf_hash() ?>';
                form.appendChild(csrfInput);

                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';
                form.appendChild(methodInput);

                document.body.appendChild(form);
                form.submit();

                userToDelete = {
                    id: null,
                    username: null,
                    fullname: null
                };
            }
        });

        selectAllCheckBox.addEventListener('change', function() {
            if (selectAllCheckBox.checked) {
                if (bulkDeleteButton)
                    bulkDeleteButton.disabled = false;
            } else {
                if (bulkDeleteButton)
                    bulkDeleteButton.disabled = true;
            }

            checkboxes.forEach(checkbox => {
                checkbox.checked = selectAllCheckBox.checked;
            });
        });

        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                let anyChecked = Array.from(checkboxes).some(cb => cb.checked);

                if (!checkbox.checked) {
                    selectAllCheckBox.checked = false;
                    selectAllCheckBox.indeterminate = anyChecked;
                    bulkDeleteButton.disabled = !anyChecked;
                } else {
                    let allChecked = Array.from(checkboxes).every(cb => cb.checked);
                    if (allChecked) {
                        selectAllCheckBox.checked = true;
                        selectAllCheckBox.indeterminate = false;
                        return;
                    }
                    selectAllCheckBox.indeterminate = true;
                    bulkDeleteButton.disabled = false;
                }
            });
        });

        bulkDeleteButton.addEventListener('click', function(event) {
            event.preventDefault();

            // Hapus input sebelumnya dari form
            bulkDeleteForm.querySelectorAll('input[name="selected_user_ids[]"]').forEach(input => input.remove());

            // Tambahkan checkbox yang dipilih ke form
            checkboxes.forEach(checkbox => {
                if (checkbox.checked) {
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = 'selected_user_ids[]';
                    hiddenInput.value = checkbox.value;
                    bulkDeleteForm.appendChild(hiddenInput);
                }
            });

            // Periksa apakah ada checkbox yang dipilih
            if (bulkDeleteForm.querySelectorAll('input[name="selected_user_ids[]"]').length > 0) {
                BSconfirmBulkDeleteModal.show();

                confirmBulkDeleteModal
                    .querySelector('#modal-confirm')
                    .addEventListener('click', function() {
                        bulkDeleteForm.submit();
                    });
            } else {
                BSnoSelectionModal.show();
            }
        });
    });
</script>
<?= $this->endSection() ?>
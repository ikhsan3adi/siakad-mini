<?= $this->extend('templates/main_layout') ?>

<?= $this->section('title') ?>
Courses Management - SIAKAD
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="container mt-4">
    <div class="row">
        <div class="col">
            <h1 class="mb-4">Courses Management</h1>
            <div class="row">
                <div class="col-12 col-md-6">
                    <a href="<?= base_url('/admin/courses/new') ?>" class="btn btn-primary mb-3"><i class="bi bi-plus"></i> Add Course</a>
                </div>
                <div class="col-12 col-md-6">
                    <form action="<?= base_url('/admin/courses') ?>" method="get" class="d-flex" role="search">
                        <div class="input-group mb-3">
                            <input class="form-control" type="search" name="keyword" placeholder="Search course" aria-label="Search" value="<?= esc(request()->getVar('keyword') ?? '') ?>">
                            <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i> Search</button>
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


            <div class="row mb-2">
                <div class="col">
                    <button id="bulkDeleteButton" type="button" class="btn btn-danger mb-3" disabled>
                        <i class="bi bi-trash"></i> Delete Selected
                    </button>
                </div>
            </div>

            <form id="bulkDeleteForm" action="<?= base_url('admin/courses/bulk-delete') ?>" method="post">
                <?= csrf_field() ?>
                <input type="hidden" name="_method" value="DELETE">
            </form>

            <?= view('templates/modal', [
                'modalId' => 'confirmBulkDeleteModal',
                'modalTitle' => 'Warning',
                'modalBody' => 'Are you sure you want to delete the selected courses?',
                'noConfirm' => false,
                'danger' => true,
                'submit' => true,
            ]) ?>

            <?= view('templates/modal', [
                'modalId' => 'noSelectionModal',
                'modalTitle' => 'No Selection',
                'modalBody' => 'Please select at least one course to delete.',
                'noConfirm' => true,
            ]) ?>

            <?= view('templates/modal', [
                'modalId' => 'confirmSingleDeleteModal',
                'noConfirm' => false,
                'danger' => true,
                'submit' => true,
            ]) ?>

            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>
                            <label for="selectAllCheckBox" class="d-block w-100 h-100 px-1 py-1 text-center">
                                <input id="selectAllCheckBox" class="form-check-input" type="checkbox" aria-label="Select all course">
                            </label>
                        </th>
                        <th>No</th>
                        <th>Course Code</th>
                        <th>Course Name</th>
                        <th>Credits</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $num = 1 + ($perPage * ($currentPage - 1)); ?>
                    <?php foreach ($courses as $course) : ?>
                        <tr>
                            <td class="p-0 m-0">
                                <?php
                                $cbId = 'bulk-' . $course['course_code'];
                                ?>
                                <label for="<?= $cbId ?>" class="d-block w-100 h-100 px-1 py-3 text-center">
                                    <input id="<?= $cbId ?>" class="form-check-input" type="checkbox" value="<?= esc($course['course_code']) ?>" name="selected_course_codes[]" aria-label="Select course">
                                </label>
                            </td>
                            <td><?= $num++ ?></td>
                            <td><?= esc($course['course_code']) ?></td>
                            <td><?= esc($course['course_name']) ?></td>
                            <td><?= esc($course['credits']) ?></td>
                            <td>
                                <a href="<?= base_url('/admin/courses/' . $course['course_code']) ?>" class="btn btn-info btn-sm">
                                    <i class="bi bi-eye"></i> Detail
                                </a>
                                <a href="<?= base_url('/admin/courses/edit/' . $course['course_code']) ?>" class="btn btn-warning btn-sm">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>

                                <button id="singleDeleteButton" type="button" class="btn btn-danger btn-sm"
                                    data-course-id="<?= esc($course['course_code']) ?>"
                                    data-course-code="<?= esc($course['course_code']) ?>"
                                    data-course-name="<?= esc($course['course_name']) ?>">
                                    <i class="bi bi-trash"></i> Delete
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($courses)) : ?>
                        <tr>
                            <td colspan="6" class="text-center">No courses found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <?= $pager->links('courses', 'my_pager'); ?>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const singleDeleteButtons = document.querySelectorAll('#singleDeleteButton');
        const bulkDeleteButton = document.getElementById('bulkDeleteButton');
        const selectAllCheckBox = document.getElementById('selectAllCheckBox');
        const checkboxes = document.querySelectorAll('input[name="selected_course_codes[]"]');
        const bulkDeleteForm = document.getElementById('bulkDeleteForm');

        const confirmSingleDeleteModal = document.getElementById('confirmSingleDeleteModal');
        const confirmSingleDeleteButton = confirmSingleDeleteModal.querySelector('#modal-confirm');

        const confirmBulkDeleteModal = document.getElementById('confirmBulkDeleteModal');
        const noSelectionModal = document.getElementById('noSelectionModal');

        const BSconfirmSingleDeleteModal = new bootstrap.Modal(confirmSingleDeleteModal);
        const BSconfirmBulkDeleteModal = new bootstrap.Modal(confirmBulkDeleteModal);
        const BSnoSelectionModal = new bootstrap.Modal(noSelectionModal);

        let courseToDelete = {
            courseId: null,
            courseCode: null,
            courseName: null
        };
        singleDeleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                courseToDelete = {
                    courseId: this.getAttribute('data-course-id'),
                    courseCode: this.getAttribute('data-course-code'),
                    courseName: this.getAttribute('data-course-name')
                };
                BSconfirmSingleDeleteModal.show();
                confirmSingleDeleteModal.querySelector('.modal-title').textContent =
                    'Delete Course "' + courseToDelete.courseCode + '"';
                confirmSingleDeleteModal.querySelector('.modal-body').textContent = `Are you sure you want to delete the course "${courseToDelete.courseName}"?`;
            });
        });

        confirmSingleDeleteButton.addEventListener('click', function() {
            if (courseToDelete.courseId) {
                const form = document.createElement('form');
                form.action = `/admin/courses/delete/${courseToDelete.courseId}`;
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

                courseToDelete = {
                    courseId: null,
                    courseCode: null,
                    courseName: null
                };
            }
        });

        selectAllCheckBox.addEventListener('change', function() {
            if (selectAllCheckBox.checked) {
                bulkDeleteButton.disabled = false;
            } else {
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
            bulkDeleteForm.querySelectorAll('input[name="selected_course_codes[]"]').forEach(input => input.remove());

            // Tambahkan checkbox yang dipilih ke form
            checkboxes.forEach(checkbox => {
                if (checkbox.checked) {
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = 'selected_course_codes[]';
                    hiddenInput.value = checkbox.value;
                    bulkDeleteForm.appendChild(hiddenInput);
                }
            });

            // Periksa apakah ada checkbox yang dipilih
            if (bulkDeleteForm.querySelectorAll('input[name="selected_course_codes[]"]').length > 0) {
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
<?= $this->extend('templates/main_layout') ?>

<?= $this->section('title') ?>
Courses - SIAKAD
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="container mt-4">
    <div class="row">
        <div class="col">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                <h1 class="mb-4">Courses</h1>

                <a href="<?= base_url('student/courses/my') ?>" class="btn btn-outline-info mb-3">
                    <i class="bi bi-arrow-left-right"></i> Show My Enrolled Courses
                </a>
            </div>

            <div class="row">
                <div class="col-12 col-md-6">
                    <form action="<?= base_url('/student/courses') ?>" method="get" class="d-flex" role="search">
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
            <?php if (session()->getFlashdata('errors')) : ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php if (is_array(session()->getFlashdata('errors'))) : ?>
                        <?php foreach (session()->getFlashdata('errors') as $error) : ?>
                            <?= $error ?>
                            <br>
                        <?php endforeach ?>
                    <?php else : ?>
                        <?= session()->getFlashdata('errors') ?>
                    <?php endif ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif ?>


            <div class="row mb-2">
                <div class="col">
                    <button id="bulkEnrollButton" type="button" class="btn btn-primary mb-3" disabled>
                        <i class="bi bi-journal-plus"></i> Enroll Selected
                    </button>
                </div>
            </div>

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
                                    <input id="<?= $cbId ?>" class="form-check-input" type="checkbox" value="<?= esc($course['course_code']) ?>" name="selected_course_codes[]" aria-label="Select course"
                                        data-course-name="<?= esc($course['course_name']) ?>" data-course-credits="<?= esc($course['credits']) ?>"
                                        <?= $course['enrolled'] ? 'disabled' : '' ?>>
                                </label>
                            </td>
                            <td><?= $num++ ?></td>
                            <td><?= esc($course['course_code']) ?></td>
                            <td>
                                <?= esc($course['course_name']) ?>
                                <?php if ($course['enrolled']) : ?>
                                    <span class="badge bg-success fw-normal">Enrolled <i class="bi bi-check"></i></span>
                                <?php endif; ?>
                            </td>
                            <td><?= esc($course['credits']) ?></td>
                            <td class="d-flex gap-2">
                                <a href="<?= base_url('/student/courses/' . $course['course_code']) ?>" class="btn btn-info btn-sm">
                                    <i class="bi bi-eye"></i> Detail
                                </a>
                                <?php if (!$course['enrolled']) : ?>
                                    <button id="singleEnrollButton" type="button" class="btn btn-primary btn-sm"
                                        data-course-id="<?= esc($course['course_code']) ?>"
                                        data-course-code="<?= esc($course['course_code']) ?>"
                                        data-course-name="<?= esc($course['course_name']) ?>">
                                        <i class="bi bi-journal-plus"></i> Enroll
                                    </button>
                                <?php endif; ?>
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
            <div class="my-3">
                <div>
                    <strong>Total Credits of Selected Courses: </strong> <span id="totalSelectedCredits">0</span>
                    <br>
                    <strong>Current Enrolled Credits: </strong> <span id="currentEnrolledCredits"><?= esc($currentStudentCredit) ?></span>
                    <br>
                    <strong>Total Credits After Enroll: </strong> <span id="totalCreditsAfterEnroll"><?= esc($currentStudentCredit) ?></span>
                </div>
            </div>
            <?= $pager->links('courses', 'my_pager'); ?>
        </div>
    </div>
</div>

<form id="bulkEnrollForm" action="<?= base_url('student/courses/bulk-enroll') ?>" method="post">
    <?= csrf_field() ?>
</form>

<?= view('templates/modal', [
    'modalId' => 'confirmBulkEnrollModal',
    'modalTitle' => 'Warning',
    'modalBody' => 'Are you sure you want to enroll the selected courses?',
    'noConfirm' => false,
    'danger' => true,
    'submit' => true,
]) ?>

<?= view('templates/modal', [
    'modalId' => 'confirmSingleEnrollModal',
    'noConfirm' => false,
    'danger' => true,
    'submit' => true,
]) ?>

<?= view('templates/modal', [
    'modalId' => 'noSelectionModal',
    'modalTitle' => 'No Selection',
    'modalBody' => 'Please select at least one course to (un)enroll.',
    'noConfirm' => true,
]) ?>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectAllCheckBox = document.getElementById('selectAllCheckBox');
        const checkboxes = document.querySelectorAll('input[name="selected_course_codes[]"]');

        const singleEnrollButtons = document.querySelectorAll('#singleEnrollButton');

        const confirmSingleEnrollModal = document.getElementById('confirmSingleEnrollModal');
        const confirmSingleEnrollButton = confirmSingleEnrollModal.querySelector('#modal-confirm');
        const BSconfirmSingleEnrollModal = new bootstrap.Modal(confirmSingleEnrollModal);

        const bulkEnrollButton = document.getElementById('bulkEnrollButton');
        const bulkEnrollForm = document.getElementById('bulkEnrollForm');

        const confirmBulkEnrollModal = document.getElementById('confirmBulkEnrollModal');
        const BSconfirmBulkEnrollModal = new bootstrap.Modal(confirmBulkEnrollModal);

        const noSelectionModal = document.getElementById('noSelectionModal');
        const BSnoSelectionModal = new bootstrap.Modal(noSelectionModal);

        selectAllCheckBox.addEventListener('change', function() {
            if (selectAllCheckBox.checked) {
                bulkEnrollButton.disabled = false;
            } else {
                bulkEnrollButton.disabled = true;
            }

            checkboxes.forEach(checkbox => {
                if (!checkbox.disabled)
                    checkbox.checked = selectAllCheckBox.checked;
            });

            updateTotalSelectedCredits();
        });

        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                let anyChecked = Array.from(checkboxes).some(cb => cb.checked);

                if (!checkbox.checked) {
                    selectAllCheckBox.checked = false;
                    selectAllCheckBox.indeterminate = anyChecked;
                    bulkEnrollButton.disabled = !anyChecked;
                } else {
                    let allChecked = Array.from(checkboxes).every(cb => cb.checked);
                    if (allChecked) {
                        selectAllCheckBox.checked = true;
                        selectAllCheckBox.indeterminate = false;
                    } else {
                        selectAllCheckBox.indeterminate = true;
                        bulkEnrollButton.disabled = false;
                    }
                }

                updateTotalSelectedCredits();
            });
        });

        // ========================== DYNAMIC CREDITS INFO

        const totalSelectedCreditsElement = document.getElementById('totalSelectedCredits');
        const currentEnrolledCreditsElement = document.getElementById('currentEnrolledCredits');
        const totalCreditsAfterEnrollElement = document.getElementById('totalCreditsAfterEnroll');
        /**
        creditValues example:
            {
                "21IF1004": 4,
                "25IF1102": 4,
                ...
            }
        */
        const creditValues = <?= json_encode(array_column($courses, 'credits', 'course_code')) ?>;

        function updateTotalSelectedCredits() {
            let totalCredits = Array.from(checkboxes)
                .filter(cb => cb.checked)
                .reduce((sum, cb) => sum + (parseInt(creditValues[cb.value]) || 0), 0);
            totalSelectedCreditsElement.textContent = totalCredits;

            // current credit + total selected credit
            let currentCredits = parseInt(currentEnrolledCreditsElement.textContent) || 0;
            totalCreditsAfterEnrollElement.textContent = currentCredits + totalCredits;
        }

        //========================== ENROLL

        let courseToEnroll = {
            courseId: null,
            courseCode: null,
            courseName: null
        };
        singleEnrollButtons.forEach(button => {
            button.addEventListener('click', function() {
                courseToEnroll = {
                    courseId: this.getAttribute('data-course-id'),
                    courseCode: this.getAttribute('data-course-code'),
                    courseName: this.getAttribute('data-course-name')
                };
                BSconfirmSingleEnrollModal.show();
                confirmSingleEnrollModal.querySelector('.modal-title').textContent =
                    'Enroll Course "' + courseToEnroll.courseCode + '"';
                confirmSingleEnrollModal.querySelector('.modal-body').innerHTML =
                    `Are you sure you want to enroll the course "${courseToEnroll.courseName}"?<br>
                     Selected Course Credits: <strong>${parseInt(creditValues[courseToEnroll.courseCode]) || 0}</strong><br>
                     Total Credits After Enroll: <strong>${parseInt(currentEnrolledCreditsElement.textContent) + (parseInt(creditValues[courseToEnroll.courseCode]) || 0)}</strong>`;
            });
        });

        confirmSingleEnrollButton.addEventListener('click', function() {
            if (courseToEnroll.courseId) {
                const form = document.createElement('form');
                form.action = `/student/courses/enroll/${courseToEnroll.courseId}`;
                form.method = 'post';

                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '<?= csrf_token() ?>';
                csrfInput.value = '<?= csrf_hash() ?>';
                form.appendChild(csrfInput);

                document.body.appendChild(form);
                form.submit();

                courseToEnroll = {
                    courseId: null,
                    courseCode: null,
                    courseName: null
                };

                form.remove();
            }
        });

        bulkEnrollButton.addEventListener('click', function(event) {
            event.preventDefault();

            // Delete existing inputs
            bulkEnrollForm.querySelectorAll('input[name="selected_course_codes[]"]').forEach(input => input.remove());

            // Add selected checkboxes to form
            checkboxes.forEach(checkbox => {
                if (checkbox.checked) {
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = 'selected_course_codes[]';
                    hiddenInput.value = checkbox.value;
                    bulkEnrollForm.appendChild(hiddenInput);
                }
            });

            // Check if any selected
            if (bulkEnrollForm.querySelectorAll('input[name="selected_course_codes[]"]').length > 0) {
                BSconfirmBulkEnrollModal.show();
                confirmBulkEnrollModal.getElementsByClassName('modal-body')[0]
                    .innerHTML = `Are you sure you want to enroll the selected courses?<br>`;

                // list of selected course
                const ul = document.createElement('ul');
                checkboxes.forEach(input => {
                    if (!input.checked) return;

                    const li = document.createElement('li');
                    const courseName = input.getAttribute('data-course-name');
                    const courseCredits = input.getAttribute('data-course-credits');
                    li.innerHTML = `${input.value}. ${courseName} (${courseCredits} credits)`;
                    ul.appendChild(li);
                });

                confirmBulkEnrollModal.getElementsByClassName('modal-body')[0]
                    .appendChild(ul);

                confirmBulkEnrollModal.getElementsByClassName('modal-body')[0]
                    .innerHTML += `Total Selected Credits: <strong>${totalSelectedCreditsElement.textContent}</strong><br>`;
                confirmBulkEnrollModal.getElementsByClassName('modal-body')[0]
                    .innerHTML += `Total Credits After Enroll: <strong>${totalCreditsAfterEnrollElement.textContent}</strong>`;

                confirmBulkEnrollModal
                    .querySelector('#modal-confirm')
                    .addEventListener('click', function() {
                        bulkEnrollForm.submit();
                    });
            } else {
                BSnoSelectionModal.show();
            }
        });
    });
</script>
<?= $this->endSection() ?>
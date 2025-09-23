<?= $this->extend('templates/main_layout') ?>

<?= $this->section('title') ?>
My Courses - SIAKAD
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="container mt-4">
    <div class="row">
        <div class="col">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                <h1 class="mb-4">My Courses</h1>

                <a href="<?= base_url('student/courses') ?>" class="btn btn-outline-info mb-3">
                    <i class="bi bi-arrow-left-right"></i> Show All Courses
                </a>
            </div>

            <div class="row">
                <div class="col-12 col-md-6">
                    <form action="<?= base_url('/student/courses/my') ?>" method="get" class="d-flex" role="search">
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
                    <button id="bulkUnEnrollButton" type="button" class="btn btn-danger mb-3" disabled>
                        <i class="bi bi-journal-x"></i> Unenroll Selected
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
                        <th>Grade</th>
                        <th>Enroll Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $num = 1; ?>
                    <?php foreach ($courses as $course) : ?>
                        <tr>
                            <td class="p-0 m-0">
                                <?php
                                $cbId = 'bulk-' . $course['course_code'];
                                ?>
                                <label for="<?= $cbId ?>" class="d-block w-100 h-100 px-1 py-3 text-center">
                                    <input id="<?= $cbId ?>" class="form-check-input" type="checkbox" value="<?= esc($course['course_code']) ?>" name="selected_course_codes[]" aria-label="Select course"
                                        data-course-name="<?= esc($course['course_name']) ?>" data-course-credits="<?= esc($course['credits']) ?>">
                                </label>
                            </td>
                            <td><?= $num++ ?></td>
                            <td><?= esc($course['course_code']) ?></td>
                            <td><?= esc($course['course_name']) ?></td>
                            <td><?= esc($course['credits']) ?></td>
                            <td>
                                <?php if (is_null($course['grade'])): ?>
                                    <span class="badge py-1 px-2 fs-6 bg-secondary">N/A</span>
                                <?php elseif ($course['grade'] >= 3.5): ?>
                                    <span class="badge py-1 px-2 fs-6 bg-success"><?= esc($course['grade']) ?></span>
                                <?php elseif ($course['grade'] >= 2.5): ?>
                                    <span class="badge py-1 px-2 fs-6 bg-white text-dark"><?= esc($course['grade']) ?></span>
                                <?php else: ?>
                                    <span class="badge py-1 px-2 fs-6 bg-danger"><?= esc($course['grade']) ?></span>
                                <?php endif; ?>
                            </td>
                            <td><?= date('d M Y', strtotime($course['enroll_date'])) ?></td>
                            <td>
                                <a href="<?= base_url('/student/courses/' . $course['course_code']) ?>" class="btn btn-info btn-sm">
                                    <i class="bi bi-eye"></i> Detail
                                </a>
                                <button id="singleUnEnrollButton" type="button" class="btn btn-danger btn-sm"
                                    data-course-id="<?= esc($course['course_code']) ?>"
                                    data-course-code="<?= esc($course['course_code']) ?>"
                                    data-course-name="<?= esc($course['course_name']) ?>">
                                    <i class="bi bi-journal-x"></i> Unenroll
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($courses)) : ?>
                        <tr>
                            <td colspan="8" class="text-center">No courses found.</td>
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
                    <strong>Total Credits After Un-enroll: </strong> <span id="totalCreditsAfterEnroll"><?= esc($currentStudentCredit) ?></span>
                </div>
            </div>
        </div>
    </div>
</div>

<form id="bulkUnEnrollForm" action="<?= base_url('student/courses/bulk-unenroll') ?>" method="post">
    <?= csrf_field() ?>
</form>


<?= view('templates/modal', [
    'modalId' => 'confirmBulkUnEnrollModal',
    'modalTitle' => 'Warning',
    'modalBody' => 'Are you sure you want to unenroll the selected courses?',
    'noConfirm' => false,
    'danger' => true,
    'submit' => true,
]) ?>

<?= view('templates/modal', [
    'modalId' => 'confirmSingleUnEnrollModal',
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

        const singleUnEnrollButtons = document.querySelectorAll('#singleUnEnrollButton');

        const confirmSingleUnEnrollModal = document.getElementById('confirmSingleUnEnrollModal');
        const confirmSingleUnEnrollButton = confirmSingleUnEnrollModal.querySelector('#modal-confirm');
        const BSconfirmSingleUnEnrollModal = new bootstrap.Modal(confirmSingleUnEnrollModal);

        const bulkUnEnrollButton = document.getElementById('bulkUnEnrollButton');
        const bulkUnEnrollForm = document.getElementById('bulkUnEnrollForm');

        const confirmBulkUnEnrollModal = document.getElementById('confirmBulkUnEnrollModal');
        const BSconfirmBulkUnEnrollModal = new bootstrap.Modal(confirmBulkUnEnrollModal);

        const noSelectionModal = document.getElementById('noSelectionModal');
        const BSnoSelectionModal = new bootstrap.Modal(noSelectionModal);

        selectAllCheckBox.addEventListener('change', function() {
            if (selectAllCheckBox.checked) {
                bulkUnEnrollButton.disabled = false;
            } else {
                bulkUnEnrollButton.disabled = true;
            }

            checkboxes.forEach(checkbox => {
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

                    bulkUnEnrollButton.disabled = !anyChecked;
                } else {
                    let allChecked = Array.from(checkboxes).every(cb => cb.checked);
                    if (allChecked) {
                        selectAllCheckBox.checked = true;
                        selectAllCheckBox.indeterminate = false;
                    } else {
                        selectAllCheckBox.indeterminate = true;
                    }
                    bulkUnEnrollButton.disabled = false;
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

            // current credit - total selected credit
            let currentCredits = parseInt(currentEnrolledCreditsElement.textContent) || 0;
            totalCreditsAfterEnrollElement.textContent = currentCredits - totalCredits;
        }

        //========================== UNENROLL

        let courseToUnEnroll = {
            courseId: null,
            courseCode: null,
            courseName: null
        };
        singleUnEnrollButtons.forEach(button => {
            button.addEventListener('click', function() {
                courseToUnEnroll = {
                    courseId: this.getAttribute('data-course-id'),
                    courseCode: this.getAttribute('data-course-code'),
                    courseName: this.getAttribute('data-course-name')
                };
                BSconfirmSingleUnEnrollModal.show();
                confirmSingleUnEnrollModal.querySelector('.modal-title').textContent =
                    'Unenroll Course "' + courseToUnEnroll.courseCode + '"';
                confirmSingleUnEnrollModal.querySelector('.modal-body').textContent = `Are you sure you want to unenroll the course "${courseToUnEnroll.courseName}"?`;
            });
        });

        confirmSingleUnEnrollButton.addEventListener('click', function() {
            if (courseToUnEnroll.courseId) {
                const form = document.createElement('form');
                form.action = `/student/courses/unenroll/${courseToUnEnroll.courseId}`;
                form.method = 'post';

                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '<?= csrf_token() ?>';
                csrfInput.value = '<?= csrf_hash() ?>';
                form.appendChild(csrfInput);

                document.body.appendChild(form);
                form.submit();

                courseToUnEnroll = {
                    courseId: null,
                    courseCode: null,
                    courseName: null
                };
            }
        });

        bulkUnEnrollButton.addEventListener('click', function(event) {
            event.preventDefault();

            // Delete existing inputs
            bulkUnEnrollForm.querySelectorAll('input[name="selected_course_codes[]"]').forEach(input => input.remove());

            // Add selected checkboxes to form
            checkboxes.forEach(checkbox => {
                if (checkbox.checked) {
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = 'selected_course_codes[]';
                    hiddenInput.value = checkbox.value;
                    bulkUnEnrollForm.appendChild(hiddenInput);
                }
            });

            // Check if any selected
            if (bulkUnEnrollForm.querySelectorAll('input[name="selected_course_codes[]"]').length > 0) {
                BSconfirmBulkUnEnrollModal.show();

                confirmBulkUnEnrollModal.getElementsByClassName('modal-body')[0]
                    .innerHTML = `Are you sure you want to unenroll the selected courses?<br>`;

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

                confirmBulkUnEnrollModal.getElementsByClassName('modal-body')[0]
                    .appendChild(ul);

                confirmBulkUnEnrollModal.getElementsByClassName('modal-body')[0]
                    .innerHTML += `Total Selected Credits: <strong>${totalSelectedCreditsElement.textContent}</strong><br>`;
                confirmBulkUnEnrollModal.getElementsByClassName('modal-body')[0]
                    .innerHTML += `Total Credits After Enroll: <strong>${totalCreditsAfterEnrollElement.textContent}</strong>`;


                confirmBulkUnEnrollModal
                    .querySelector('#modal-confirm')
                    .addEventListener('click', function() {
                        bulkUnEnrollForm.submit();
                    });
            } else {
                BSnoSelectionModal.show();
            }
        });

    });
</script>
<?= $this->endSection() ?>
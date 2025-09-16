<?php

// check current path to set active class
/** @var \CodeIgniter\HTTP\URI $uri */
$uri = service('uri');
$currentPath = $uri->getPath();

// remove index.php from the path if exists
$currentPath = str_replace('/index.php/', '', $currentPath);
$currentPath = str_replace('index.php/', '', $currentPath);

helper('text');

?>
<div class="d-flex flex-column flex-shrink-0 p-3 bg-body-secondary" style="width: 280px;">
    <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-body-emphasis text-decoration-none">
        <i class="bi bi-mortarboard-fill pe-none me-3" style="font-size: 24pt;"></i>
        <span class="fs-4">
            <?php if (auth()->user()->inGroup('admin')): ?>
                Admin - SIAKAD
            <?php else: ?>
                SIAKAD
            <?php endif ?>
        </span>
    </a>
    <hr>
    <ul class="nav nav-pills flex-column mb-auto">
        <?php if (auth()->user()->inGroup('admin')): ?>
            <li>
                <a href="<?= base_url('admin/dashboard') ?>" class="nav-link <?= $currentPath === 'admin' || $currentPath === 'admin/dashboard' ? 'active' : 'link-body-emphasis' ?>">
                    <i class="bi bi-speedometer pe-none me-2"></i>
                    Dashboard
                </a>
            </li>
            <li>
                <a href="<?= base_url('admin/courses') ?>" class="nav-link <?= str_starts_with($currentPath, 'admin/courses') ? 'active' : 'link-body-emphasis' ?>">
                    <i class="bi bi-book-half pe-none me-2"></i>
                    Courses
                </a>
            </li>
            <li>
                <a href="<?= base_url('admin/users') ?>" class="nav-link <?= str_starts_with($currentPath, 'admin/users') ? 'active' : 'link-body-emphasis' ?>">
                    <i class="bi bi-person-fill-gear pe-none me-2"></i>
                    Users
                </a>
            </li>
        <?php else: ?>
            <li>
                <a href="<?= base_url('student/dashboard') ?>" class="nav-link <?= $currentPath === 'student' || $currentPath === 'student/dashboard' ? 'active' : 'link-body-emphasis' ?>">
                    <i class="bi bi-speedometer pe-none me-2"></i>
                    Dashboard
                </a>
            </li>
            <li>
                <a href="<?= base_url('student/courses') ?>" class="nav-link <?= str_starts_with($currentPath, 'student/courses') ? 'active' : 'link-body-emphasis' ?>">
                    <i class="bi bi-book-half pe-none me-2"></i>
                    Courses
                </a>
            </li>
        <?php endif ?>
        <li>
            <hr>
        </li>
        <li>
            <label for="toggleThemeCheckbox" class="w-100">
                <div class="nav-link link-body-emphasis d-flex justify-content-between">
                    <div class="me-5">
                        <i id="toggleThemeIcon" class="bi bi-sun-fill pe-none me-2"></i> Dark Mode
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" id="toggleThemeCheckbox" onclick="toggleTheme()">
                    </div>
                </div>
            </label>
        </li>
    </ul>
    <hr>
    <div class="dropdown">
        <a href="#" class="d-flex align-items-center link-body-emphasis text-decoration-none" data-bs-toggle="dropdown" aria-expanded="false">
            <img src="https://api.dicebear.com/9.x/identicon/svg?backgroundColor=ffffff&seed=<?= auth()->user()->username ?>" alt="profile-image" width="32" height="32" class="rounded-circle me-2">
            <span class="d-flex flex-column">
                <strong>
                    <?= ellipsize(auth()->user()->full_name, 22) ?>
                </strong>
                <small class="text-muted">
                    <?= auth()->user()->username ?>
                </small>
            </span>
        </a>
        <ul class="dropdown-menu text-small shadow">
            <!-- <li><a class="dropdown-item" href="#">Settings</a></li> -->
            <!-- <li><a class="dropdown-item" href="#">Profile</a></li> -->
            <!-- <li>
                <hr class="dropdown-divider">
            </li> -->
            <li><a class="dropdown-item" href="<?= base_url('logout') ?>">Sign out</a></li>
        </ul>
    </div>
</div>

<script>
    // set theme based on local storage
    const savedTheme = localStorage.getItem('theme') || 'dark';
    document.documentElement.setAttribute('data-bs-theme', savedTheme);
    const toggleThemeCheckbox = document.getElementById('toggleThemeCheckbox');
    const icon = document.getElementById('toggleThemeIcon');

    if (savedTheme === 'dark') {
        toggleThemeCheckbox.checked = true;

        icon.classList.remove('bi-sun-fill');
        icon.classList.add('bi-moon-fill');
    } else {
        toggleThemeCheckbox.checked = false;

        icon.classList.remove('bi-moon-fill');
        icon.classList.add('bi-sun-fill');
    }

    function toggleTheme() {
        const currentTheme = document.documentElement.getAttribute('data-bs-theme');
        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
        document.documentElement.setAttribute('data-bs-theme', newTheme);

        localStorage.setItem('theme', newTheme);

        const icon = document.getElementById('toggleThemeIcon');
        if (newTheme === 'dark') {
            icon.classList.remove('bi-sun-fill');
            icon.classList.add('bi-moon-fill');
        } else {
            icon.classList.remove('bi-moon-fill');
            icon.classList.add('bi-sun-fill');
        }
    }
</script>
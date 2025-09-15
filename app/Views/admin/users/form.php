<?= $this->extend('templates/main_layout') ?>

<?= $this->section('title') ?>
<?= isset($course) ? 'Edit User - SIAKAD' : 'New User - SIAKAD' ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?= $this->endSection() ?>
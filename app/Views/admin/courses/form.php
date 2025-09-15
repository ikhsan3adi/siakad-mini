<?= $this->extend('templates/main_layout') ?>

<?= $this->section('title') ?>
<?= isset($course) ? 'Edit Course - SIAKAD' : 'New Course - SIAKAD' ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?= $this->endSection() ?>
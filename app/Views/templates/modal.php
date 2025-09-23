<div class="modal fade" id="<?= $modalId ?>" tabindex="-1" aria-labelledby="<?= $modalLabel ?? 'modal_label' ?>" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="<?= $modalLabel ?? 'modal_label' ?>"><?= $modalTitle ?? 'Warning' ?></h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?= $modalBody ?? 'Are you sure?' ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn <?= ($danger ?? false) ? 'btn-primary' : 'btn-secondary' ?>" data-bs-dismiss="modal">Close</button>
                <?php if (!($noConfirm ?? false)): ?>
                    <button id="modal-confirm" type="<?= ($submit ?? false) ? 'submit' : 'button' ?>" class="btn <?= ($danger ?? false) ? 'btn-secondary' : 'btn-primary' ?>">OK</button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
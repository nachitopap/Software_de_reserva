<?php if (!empty($pageAlerts)): ?>
<div class="swal-alert-store" hidden>
    <?php foreach ($pageAlerts as $alert): ?>
        <div
            class="swal-alert-item"
            data-type="<?= htmlspecialchars((string)($alert['type'] ?? 'info')) ?>"
            data-message="<?= htmlspecialchars((string)($alert['message'] ?? '')) ?>"
        ></div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

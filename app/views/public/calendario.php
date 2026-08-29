<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendario – Software de Reservas</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body class="public-page">
<main class="public-shell public-shell-wide">
    <section class="public-card public-summary-card">
        <div class="public-header">
            <h1>Registro completado</h1>
            <p>Estos son los datos ingresados antes de elegir un día.</p>
        </div>

        <dl class="summary-list">
            <div>
                <dt>Nombre</dt>
                <dd><?= htmlspecialchars($guestProfile['nombre'] . ' ' . $guestProfile['apellidos']) ?></dd>
            </div>
            <div>
                <dt>Teléfono</dt>
                <dd><?= htmlspecialchars($guestProfile['telefono']) ?></dd>
            </div>
            <div>
                <dt>Email</dt>
                <dd><?= htmlspecialchars($guestProfile['email']) ?></dd>
            </div>
        </dl>

        <a href="formulario.php" class="btn btn-secondary">
            <i class="bi bi-pencil-square" aria-hidden="true"></i>
            <span>Editar formulario</span>
        </a>
    </section>

    <section class="public-card public-calendar-card">
        <div class="calendar-header">
            <div>
                <h2><?= htmlspecialchars($currentMonth->format('F Y')) ?></h2>
                <p>Seleccion futura: por ahora solo mostramos los días del mes.</p>
            </div>
            <div class="calendar-nav">
                <a href="calendario.php?mes=<?= htmlspecialchars($previousMonth) ?>" class="btn btn-secondary">
                    <i class="bi bi-chevron-left" aria-hidden="true"></i>
                    <span>Anterior</span>
                </a>
                <a href="calendario.php?mes=<?= htmlspecialchars($nextMonth) ?>" class="btn btn-secondary">
                    <span>Siguiente</span>
                    <i class="bi bi-chevron-right" aria-hidden="true"></i>
                </a>
            </div>
        </div>

        <div class="calendar-grid calendar-grid-head">
            <span>Lun</span>
            <span>Mar</span>
            <span>Mié</span>
            <span>Jue</span>
            <span>Vie</span>
            <span>Sáb</span>
            <span>Dom</span>
        </div>

        <div class="calendar-grid calendar-grid-days">
            <?php foreach ($calendarCells as $day): ?>
                <div class="calendar-cell<?= $day === null ? ' is-empty' : '' ?>">
                    <?= $day === null ? '' : (int)$day ?>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
</main>
<?php include __DIR__ . '/../layouts/swal-alerts.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="js/app.js"></script>
</body>
</html>

<?php
require_once __DIR__ . '/../../../config/funciones.php';
require_once __DIR__ . '/../../controllers/ServicioController.php';

$servicios = (new ServicioController())->listarActivos();
$isLoggedIn = !empty($_SESSION['user_id']);
$pageAlerts = [];
if ($flashAlert = consumeFlashAlert()) {
    $pageAlerts[] = $flashAlert;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio | Software de Reservas</title>
    <link rel="stylesheet" href="<?= htmlspecialchars(appPath('public/css/styles.css')) ?>">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .home-page { background: #eef4ff; }
        .home-main { min-height: calc(100vh - 62px); padding: 3.5rem clamp(1.25rem, 5vw, 5rem); overflow: hidden; }
        .home-intro { max-width: 760px; margin: 0 auto 2.5rem; text-align: center; }
        .home-eyebrow { color: var(--color-primary); font-size: .78rem; font-weight: 800; letter-spacing: .12em; text-transform: uppercase; }
        .home-intro h1 { margin-top: .65rem; font-size: clamp(2rem, 5vw, 4.2rem); line-height: 1.05; }
        .home-intro p { max-width: 560px; margin: 1rem auto 0; color: var(--color-text-muted); font-size: 1.08rem; }
        .service-carousel { display: flex; gap: 1rem; overflow-x: auto; scroll-snap-type: x mandatory; padding: .5rem .25rem 1.5rem; scrollbar-width: thin; }
        .service-slide { flex: 0 0 min(330px, 82vw); min-height: 220px; padding: 1.5rem; scroll-snap-align: start; border: 1px solid #cbdaf5; border-radius: .8rem; background: linear-gradient(145deg, #fff, #e4edff); box-shadow: 0 14px 30px rgba(37, 99, 235, .12); }
        .service-slide:nth-child(3n + 2) { background: linear-gradient(145deg, #fff, #e5f7f3); border-color: #b9e5dc; }
        .service-slide:nth-child(3n + 3) { background: linear-gradient(145deg, #fff, #fff1dc); border-color: #f2d4a5; }
        .service-slide i { display: inline-grid; width: 2.7rem; height: 2.7rem; place-items: center; border-radius: 50%; color: #fff; background: var(--color-primary); font-size: 1.25rem; }
        .service-slide h2 { margin: 1.25rem 0 .45rem; font-size: 1.3rem; }
        .service-slide p { min-height: 2.8rem; color: var(--color-text-muted); }
        .service-meta { display: flex; justify-content: space-between; gap: .5rem; margin-top: 1.25rem; font-weight: 800; }
        .home-action { margin-top: 2rem; text-align: center; }
        .home-action .btn { padding: .9rem 1.4rem; font-size: 1rem; cursor: pointer; }
        .empty-services { padding: 2rem; color: var(--color-text-muted); text-align: center; }
        .booking-form { display: grid; gap: .75rem; text-align: left; }
        .booking-form label { font-size: .88rem; font-weight: 700; }
        .booking-form select, .booking-form input, .booking-form textarea { width: 100%; padding: .7rem; border: 1px solid var(--color-border); border-radius: .4rem; font: inherit; }
    </style>
</head>
<body class="home-page">
<?php include __DIR__ . '/../layouts/navbar_client.php'; ?>
<main class="home-main">
    <section class="home-intro">
        <span class="home-eyebrow">Tu tiempo, bien reservado</span>
        <h1>Encuentra tu próximo servicio</h1>
        <p>Explora las opciones disponibles y elige el momento que mejor se adapta a ti.</p>
    </section>

    <section class="service-carousel" aria-label="Servicios disponibles">
        <?php if ($servicios): ?>
            <?php foreach ($servicios as $servicio): ?>
                <article class="service-slide">
                    <i class="bi bi-stars" aria-hidden="true"></i>
                    <h2><?= htmlspecialchars($servicio['nombre']) ?></h2>
                    <p><?= htmlspecialchars($servicio['descripcion'] ?: 'Un servicio pensado para ti.') ?></p>
                    <div class="service-meta">
                        <span>$<?= number_format((float)$servicio['precio'], 2) ?></span>
                        <span><?= (int)$servicio['duracion_min'] ?> min</span>
                    </div>
                </article>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="empty-services">Todavía no hay servicios publicados.</p>
        <?php endif; ?>
    </section>

    <div class="home-action">
        <button type="button" class="btn btn-primary" id="new-booking-button">
            <i class="bi bi-calendar-plus" aria-hidden="true"></i>
            <span>Crear una reserva</span>
        </button>
    </div>
</main>

<template id="booking-form-template">
    <form class="booking-form" method="POST" action="<?= htmlspecialchars(appPath('public/cliente/nueva_reserva.php')) ?>">
        <label for="booking-service">Servicio</label>
        <select id="booking-service" name="servicio_id" required>
            <option value="">Selecciona un servicio</option>
            <?php foreach ($servicios as $servicio): ?>
                <option value="<?= (int)$servicio['id'] ?>"><?= htmlspecialchars($servicio['nombre']) ?> - $<?= number_format((float)$servicio['precio'], 2) ?></option>
            <?php endforeach; ?>
        </select>
        <label for="booking-date">Fecha y hora</label>
        <input id="booking-date" type="datetime-local" name="fecha_reserva" required>
        <label for="booking-notes">Notas adicionales</label>
        <textarea id="booking-notes" name="notas" rows="3" placeholder="Opcional..."></textarea>
    </form>
</template>
<?php include __DIR__ . '/../layouts/swal-alerts.php'; ?>
<script>
    document.getElementById('new-booking-button').addEventListener('click', function () {
        const form = document.getElementById('booking-form-template').content.cloneNode(true).firstElementChild;

        Swal.fire({
            title: 'Crear una reserva',
            html: form,
            showCancelButton: true,
            confirmButtonText: 'Confirmar reserva',
            cancelButtonText: 'Cancelar',
            focusConfirm: false,
            preConfirm: function () {
                if (!form.reportValidity()) {
                    return false;
                }
                form.submit();
            }
        });
    });
</script>
</body>
</html>
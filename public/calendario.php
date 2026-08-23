<?php
require_once __DIR__ . '/../config/funciones.php';

if (empty($_SESSION['guest_booking_profile'])) {
    setFlashAlert('error', 'Primero debes completar el formulario.');
    header('Location: ' . appPath('public/formulario.php'));
    exit;
}

$pageAlerts = [];
if ($flashAlert = consumeFlashAlert()) {
    $pageAlerts[] = $flashAlert;
}

$guestProfile = $_SESSION['guest_booking_profile'];
$monthInput = trim($_GET['mes'] ?? date('Y-m'));
$currentMonth = DateTime::createFromFormat('Y-m', $monthInput);

if (!$currentMonth) {
    $currentMonth = new DateTime('first day of this month');
} else {
    $currentMonth->setDate((int)$currentMonth->format('Y'), (int)$currentMonth->format('m'), 1);
}

$calendarStart = clone $currentMonth;
$daysInMonth = (int)$currentMonth->format('t');
$firstWeekday = (int)$currentMonth->format('N');
$calendarCells = [];

for ($i = 1; $i < $firstWeekday; $i++) {
    $calendarCells[] = null;
}

for ($day = 1; $day <= $daysInMonth; $day++) {
    $calendarCells[] = $day;
}

$previousMonth = (clone $currentMonth)->modify('-1 month')->format('Y-m');
$nextMonth = (clone $currentMonth)->modify('+1 month')->format('Y-m');

include __DIR__ . '/../app/views/public/calendario.php';

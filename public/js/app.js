/**
 * app.js – Software de Reservas
 * Funcionalidades básicas del lado del cliente
 */

document.addEventListener('DOMContentLoaded', () => {

    // Establecer fecha/hora mínima en campos datetime-local
    const datetimeInputs = document.querySelectorAll('input[type="datetime-local"]');
    datetimeInputs.forEach(input => {
        const now = new Date();
        now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
        input.min = now.toISOString().slice(0, 16);
    });

    // Auto-cerrar alertas después de 5 segundos
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity .4s';
            alert.style.opacity    = '0';
            setTimeout(() => alert.remove(), 400);
        }, 5000);
    });

    // Confirmación genérica mediante data-confirm
    document.querySelectorAll('[data-confirm]').forEach(el => {
        el.addEventListener('click', e => {
            if (!confirm(el.dataset.confirm)) {
                e.preventDefault();
            }
        });
    });

});

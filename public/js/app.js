/**
 * app.js – Software de Reservas
 * Funcionalidades básicas del lado del cliente
 */

document.addEventListener('DOMContentLoaded', () => {
    const swalAlerts = Array.from(document.querySelectorAll('.swal-alert-item')).map(alert => ({
        type: alert.dataset.type || 'info',
        message: alert.dataset.message || ''
    })).filter(alert => alert.message !== '');

    const fireSwalAlerts = async () => {
        if (typeof Swal === 'undefined') {
            return;
        }

        for (const alert of swalAlerts) {
            await Swal.fire({
                icon: alert.type,
                text: alert.message,
                confirmButtonText: 'Aceptar',
                timer: alert.type === 'success' ? 2200 : undefined,
                timerProgressBar: alert.type === 'success',
                showConfirmButton: alert.type !== 'success',
            });
        }
    };

    fireSwalAlerts();

    // Establecer fecha/hora mínima en campos datetime-local
    const datetimeInputs = document.querySelectorAll('input[type="datetime-local"]');
    datetimeInputs.forEach(input => {
        const now = new Date();
        now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
        input.min = now.toISOString().slice(0, 16);
    });

    // Confirmación genérica mediante data-confirm
    document.querySelectorAll('[data-confirm]').forEach(el => {
        el.addEventListener('click', e => {
            if (!confirm(el.dataset.confirm)) {
                e.preventDefault();
            }
        });
    });

    const modalToggles = document.querySelectorAll('[data-modal-target]');
    const modalCloseButtons = document.querySelectorAll('[data-modal-close]');

    const openModal = modalId => {
        const modal = document.getElementById(modalId);
        if (!modal) {
            return;
        }

        modal.classList.add('is-open');
        modal.setAttribute('aria-hidden', 'false');
    };

    const closeModal = modalId => {
        const modal = document.getElementById(modalId);
        if (!modal) {
            return;
        }

        modal.classList.remove('is-open');
        modal.setAttribute('aria-hidden', 'true');
    };

    modalToggles.forEach(button => {
        button.addEventListener('click', () => {
            const modalId = button.dataset.modalTarget;

            if (modalId === 'edit-user-modal') {
                const map = {
                    'edit-usuario-id': button.dataset.userId || '',
                    'edit-rut': button.dataset.userRut || '',
                    'edit-nombre': button.dataset.userNombre || '',
                    'edit-sobrenombre': button.dataset.userSobrenombre || '',
                    'edit-apellidop': button.dataset.userApellidop || '',
                    'edit-apellidom': button.dataset.userApellidom || '',
                    'edit-email': button.dataset.userEmail || '',
                    'edit-rol': button.dataset.userRol || ''
                };

                Object.keys(map).forEach(fieldId => {
                    const field = document.getElementById(fieldId);
                    if (field) {
                        field.value = map[fieldId];
                    }
                });

                const passwordField = document.getElementById('edit-password');
                if (passwordField) {
                    passwordField.value = '';
                }
            }

            if (modalId === 'status-user-modal') {
                const userIdField = document.getElementById('status-usuario-id');
                const userNameField = document.getElementById('status-usuario-nombre');
                const userActiveField = document.getElementById('status-usuario-activo');
                const userNameLabel = document.getElementById('status-user-name-label');
                const title = document.getElementById('status-user-modal-title');
                const subtitle = document.querySelector('#status-user-modal .modal-subtitle');
                const submitButton = document.querySelector('#status-user-modal button[type="submit"]');
                const submitIcon = submitButton ? submitButton.querySelector('i') : null;
                const submitText = submitButton ? submitButton.querySelector('span') : null;
                const isActive = button.dataset.userActive === '1';

                if (userIdField) {
                    userIdField.value = button.dataset.userId || '';
                }
                if (userNameField) {
                    userNameField.value = button.dataset.userName || '';
                }
                if (userActiveField) {
                    userActiveField.value = isActive ? '1' : '0';
                }
                if (userNameLabel) {
                    userNameLabel.textContent = button.dataset.userName || '';
                }
                if (title) {
                    title.textContent = isActive ? 'Inactivar usuario' : 'Activar usuario';
                }
                if (subtitle) {
                    subtitle.textContent = isActive
                        ? 'El usuario no se eliminará; solo quedará inactivo.'
                        : 'El usuario volverá a poder ingresar al sistema.';
                }
                if (submitButton) {
                    submitButton.classList.toggle('btn-danger', isActive);
                    submitButton.classList.toggle('btn-success', !isActive);
                }
                if (submitIcon) {
                    submitIcon.className = 'bi ' + (isActive ? 'bi-person-dash' : 'bi-person-check');
                }
                if (submitText) {
                    submitText.textContent = isActive ? 'Confirmar inactivación' : 'Confirmar activación';
                }
            }

            openModal(modalId);
        });
    });

    modalCloseButtons.forEach(button => {
        button.addEventListener('click', () => closeModal(button.dataset.modalClose));
    });

    document.querySelectorAll('.modal-backdrop').forEach(modal => {
        let startedOnBackdrop = false;

        modal.addEventListener('mousedown', event => {
            startedOnBackdrop = event.target === modal;
        });

        modal.addEventListener('mouseup', event => {
            if (startedOnBackdrop && event.target === modal) {
                closeModal(modal.id);
            }

            startedOnBackdrop = false;
        });

        modal.addEventListener('mouseleave', () => {
            startedOnBackdrop = false;
        });

        modal.addEventListener('dragstart', () => {
            startedOnBackdrop = false;
        });
    });

    document.addEventListener('keydown', event => {
        if (event.key === 'Escape') {
            event.preventDefault();
        }
    });

});

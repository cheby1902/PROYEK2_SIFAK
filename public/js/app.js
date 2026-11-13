
function openModal(id, btn = null) {
    const modal = document.getElementById(id);

    if (!modal) return;

    const form = modal.querySelector('form');

    if (btn && form) {
        form.reset();

        if (btn.dataset.action) {
            form.action = btn.dataset.action;
        }

        if (form.elements.role) {
            form.elements.role.readOnly =
                btn.dataset.lockRole === '1';
        }

        if (btn.dataset.values) {
            const values = JSON.parse(btn.dataset.values);

            Object.keys(values).forEach(key => {
                if (form.elements[key]) {
                    form.elements[key].value = values[key];
                }
            });
        }
    }

    modal.classList.add('open');
}

function closeModal(id) {
    const modal = document.getElementById(id);

    if (modal) {
        modal.classList.remove('open');
    }
}

// Hubungkan semua tombol buka dan tutup modal
document.addEventListener('click', function (e) {
    const openButton = e.target.closest('[data-open-modal]');

    if (openButton) {
        openModal(openButton.dataset.openModal, openButton);
        return;
    }

    const closeButton = e.target.closest('[data-close-modal]');

    if (closeButton) {
        closeModal(closeButton.dataset.closeModal);
    }
});

// Tutup modal dengan tombol Escape
document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
        document.querySelectorAll('.modal.open').forEach(modal => {
            modal.classList.remove('open');
        });
    }
});



// Konfirmasi sebelum menghapus data
document.addEventListener('submit', function (e) {
    const form = e.target;

    if (form.matches('form[data-confirm]')) {
        const pesan = form.dataset.confirm;

        if (!confirm(pesan)) {
            e.preventDefault();
        }
    }
});

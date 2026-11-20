
document.addEventListener('DOMContentLoaded', function () {

    // =========================
    // SIDEBAR MOBILE
    // =========================
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');

    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', function () {
            sidebar.classList.toggle('sidebar-open');
        });
    }

    // =========================
    // ALERT SUKSES
    // =========================
    const successAlert = document.getElementById('successAlert');
    const alertClose = document.getElementById('alertClose');

    function closeAlert() {
        if (!successAlert) return;

        successAlert.classList.add('alert-hidden');

        setTimeout(function () {
            successAlert.remove();
        }, 500);
    }

    if (successAlert) {
        setTimeout(closeAlert, 4000);
    }

    if (alertClose) {
        alertClose.addEventListener('click', closeAlert);
    }

    // =========================
    // PREVIEW FOTO
    // =========================
    const fotoButtons = document.querySelectorAll('.foto-btn');
    const fotoModal = document.getElementById('fotoModal');
    const previewFoto = document.getElementById('previewFoto');
    const fotoModalClose = document.getElementById('fotoModalClose');

    fotoButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            const foto = button.dataset.foto;

            if (foto && fotoModal && previewFoto) {
                previewFoto.src = foto;
                fotoModal.classList.add('modal-active');
            }
        });
    });

    function closeFotoModal() {
        if (fotoModal && previewFoto) {
            fotoModal.classList.remove('modal-active');
            previewFoto.src = '';
        }
    }

    if (fotoModalClose) {
        fotoModalClose.addEventListener('click', closeFotoModal);
    }

    // Klik area luar gambar untuk menutup modal
    if (fotoModal) {
        fotoModal.addEventListener('click', function (event) {
            if (event.target === fotoModal) {
                closeFotoModal();
            }
        });
    }

    // Tombol Escape
    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            closeFotoModal();

            if (sidebar) {
                sidebar.classList.remove('sidebar-open');
            }
        }
    });

    // =========================
    // KONFIRMASI HAPUS
    // =========================
    const formHapus = document.querySelectorAll('.form-hapus');

    formHapus.forEach(function (form) {
        form.addEventListener('submit', function (event) {
            const yakin = confirm('Yakin ingin menghapus pengaduan ini?');

            if (!yakin) {
                event.preventDefault();
            }
        });
    });

});

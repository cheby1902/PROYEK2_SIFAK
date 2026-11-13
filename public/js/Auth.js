
document.addEventListener('DOMContentLoaded', function () {

    // Tampilkan / sembunyikan password
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');

    if (togglePassword && passwordInput) {

        togglePassword.addEventListener('click', function () {

            const isPassword =
                passwordInput.type === 'password';

            passwordInput.type = isPassword
                ? 'text'
                : 'password';

            togglePassword.innerHTML = isPassword
                ? '<i class="bi bi-eye-slash"></i>'
                : '<i class="bi bi-eye"></i>';

        });
    }

    // Notifikasi berhasil logout
    if (typeof logoutSuccessMessage !== 'undefined'
        && logoutSuccessMessage) {

        Swal.fire({
            icon: 'success',
            title: 'Berhasil Logout',
            text: logoutSuccessMessage,
            confirmButtonColor: '#f39a32'
        });

    }

});

// Konfirmasi logout
document.addEventListener('click', function (event) {

    const logoutButton = event.target.closest('[data-logout]');

    if (!logoutButton) {
        return;
    }

    event.preventDefault();

    const form = logoutButton.closest('form');

    if (!form) {
        return;
    }

    Swal.fire({
        title: 'Apakah kamu yakin?',
        text: 'Kamu akan keluar dari akun SIFAK.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Logout',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#f39a32',
        cancelButtonColor: '#6c757d'
    }).then((result) => {

        if (result.isConfirmed) {
            form.submit();
        }

    });

});

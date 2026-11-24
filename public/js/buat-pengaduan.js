document.addEventListener('DOMContentLoaded', function () {

    // Toggle sidebar (layar kecil)
    var sidebar = document.getElementById('sidebar');
    var toggle = document.getElementById('sidebarToggle');
    if (toggle && sidebar) {
        toggle.addEventListener('click', function () {
            sidebar.classList.toggle('collapsed');
        });
    }

    // Preview foto + nama file
    var input = document.getElementById('foto');
    var text = document.getElementById('uploadText');
    var preview = document.getElementById('uploadPreview');

    if (input) {
        input.addEventListener('change', function () {
            var file = input.files[0];

            if (!file) {
                text.textContent = 'Klik untuk mengunggah foto';
                preview.style.display = 'none';
                preview.removeAttribute('src');
                return;
            }

            text.textContent = file.name;
            preview.src = URL.createObjectURL(file);
            preview.style.display = 'block';
        });
    }
});
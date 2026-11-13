document.addEventListener('DOMContentLoaded', () => {
    const select = document.getElementById('status');
    const note = document.getElementById('wajib');
    if (!select || !note) return;

    const toggle = () => {
        note.style.display = select.value === 'Ditolak' ? 'inline' : 'none';
    };

    select.addEventListener('change', toggle);
    toggle();
});
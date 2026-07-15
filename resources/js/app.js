import './bootstrap';
import * as bootstrap from 'bootstrap';
import '../sass/app.scss';

// Inisialisasi Tooltips & Popovers Bootstrap
document.addEventListener("DOMContentLoaded", function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });

    // Tampilkan Toast Otomatis jika ada
    var toastElList = [].slice.call(document.querySelectorAll('.toast'))
    var toastList = toastElList.map(function (toastEl) {
        var toast = new bootstrap.Toast(toastEl, { autohide: true, delay: 5000 });
        toast.show();
        return toast;
    });
});

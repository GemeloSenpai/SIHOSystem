import './bootstrap';

import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();

import Swal from 'sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css'; // estilos locales de SweetAlert2
window.Swal = Swal;

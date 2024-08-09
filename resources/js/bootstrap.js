import axios from 'axios';
import Swal from 'sweetalert2';

import flatpickr from 'flatpickr';
import select2 from 'select2';

window.axios = axios;
window.Swal = Swal;
window.flatpickr = flatpickr;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

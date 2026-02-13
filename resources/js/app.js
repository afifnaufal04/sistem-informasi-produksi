import './bootstrap';

import Swal from 'sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';

import Alpine from 'alpinejs';

window.Alpine = Alpine;
window.Swal = Swal;

Alpine.start();

document.addEventListener('DOMContentLoaded', () => {
	document.querySelectorAll('form[data-swal-delete]').forEach((form) => {
		form.addEventListener('submit', (event) => {
			event.preventDefault();

			const itemName = form.getAttribute('data-swal-delete') || 'data ini';

			Swal.fire({
				title: 'Konfirmasi Hapus',
				text: `Apakah Anda yakin ingin menghapus ${itemName}?`,
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#dc2626',
				cancelButtonColor: '#6b7280',
				confirmButtonText: 'Ya, hapus',
				cancelButtonText: 'Batal',
			}).then((result) => {
				if (result.isConfirmed) {
					form.submit();
				}
			});
		});
	});

	document.querySelectorAll('form[data-swal-confirm]').forEach((form) => {
		form.addEventListener('submit', (event) => {
			event.preventDefault();

			const title = form.getAttribute('data-swal-title') || 'Konfirmasi';
			const text = form.getAttribute('data-swal-text') || 'Lanjutkan aksi ini?';

			Swal.fire({
				title,
				text,
				icon: 'question',
				showCancelButton: true,
				confirmButtonColor: '#2563eb',
				cancelButtonColor: '#6b7280',
				confirmButtonText: 'Ya, lanjut',
				cancelButtonText: 'Batal',
			}).then((result) => {
				if (result.isConfirmed) {
					form.submit();
				}
			});
		});
	});
});

import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// Transaction page functionality
document.addEventListener('DOMContentLoaded', function() {
    // Auto scroll to cart if hash is present
    if (window.location.hash === '#daftar-belanja') {
        const cartSection = document.getElementById('daftar-belanja');
        if (cartSection) {
            cartSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }

    // Check if we're on the transaction index page
    if (document.querySelector('.add-to-cart-btn')) {
        // Handle AJAX form submission for add to cart
        document.querySelectorAll('.add-to-cart-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();

                const form = this.closest('form');
                const formData = new FormData(form);

                // Add AJAX header
                const xhr = new XMLHttpRequest();
                xhr.open('POST', form.action, true);
                xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector(
                    'meta[name="csrf-token"]').getAttribute('content'));

                xhr.onload = function() {
                    if (xhr.status === 200) {
                        const response = JSON.parse(xhr.responseText);

                        if (response.success) {
                            // Reload the page to update the cart display
                            location.reload();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message || 'Terjadi kesalahan'
                            });
                        }
                    } else {
                        // Fallback to normal form submission if AJAX fails
                        form.submit();
                    }
                };

                xhr.onerror = function() {
                    // Fallback to normal form submission
                    form.submit();
                };

                xhr.send(formData);
            });
        });

        // Handle increase quantity
        document.querySelectorAll('.increase-qty').forEach(button => {
            button.addEventListener('click', function() {
                const index = this.getAttribute('data-index');
                updateQuantity(index, 'increase');
            });
        });

        // Handle decrease quantity
        document.querySelectorAll('.decrease-qty').forEach(button => {
            button.addEventListener('click', function() {
                const index = this.getAttribute('data-index');
                updateQuantity(index, 'decrease');
            });
        });

        function updateQuantity(index, action) {
            const xhr = new XMLHttpRequest();
            const url = '/transaksi/update-cart-quantity/' + index;
            xhr.open('POST', url, true);
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').getAttribute(
                'content'));
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

            xhr.onload = function() {
                if (xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        location.reload();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message || 'Terjadi kesalahan'
                        });
                    }
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Terjadi kesalahan saat memperbarui jumlah'
                    });
                }
            };

            xhr.send('index=' + index + '&action=' + action);
        }

        // Handle remove item with SweetAlert
        document.querySelectorAll('.remove-item-btn').forEach(button => {
            button.addEventListener('click', function() {
                const index = this.getAttribute('data-index');

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: 'Item ini akan dihapus dari keranjang!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Create a form and submit it
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = '/transaksi/remove-from-cart/' + index;

                        const csrfToken = document.createElement('input');
                        csrfToken.type = 'hidden';
                        csrfToken.name = '_token';
                        csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                        form.appendChild(csrfToken);

                        const methodField = document.createElement('input');
                        methodField.type = 'hidden';
                        methodField.name = '_method';
                        methodField.value = 'DELETE';
                        form.appendChild(methodField);

                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            });
        });

        // Function to open payment modal
        window.openPaymentModal = function() {
            window.dispatchEvent(new CustomEvent('open-modal', {
                detail: 'payment-confirmation-modal'
            }));
        };

        // Function to handle payment form submission
        window.handlePaymentSubmit = function(event) {
            event.preventDefault();

            const form = event.target;
            const formData = new FormData(form);

            // Submit via AJAX
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Close modal
                    window.dispatchEvent(new CustomEvent('close-modal', {
                        detail: 'payment-confirmation-modal'
                    }));

                    // Show success popup
                    Swal.fire({
                        title: 'Berhasil!',
                        text: 'Pesanan telah berhasil dikonfirmasi.',
                        icon: 'success',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        // Redirect to confirm page or reload
                        window.location.href = '/transaksi/confirm';
                    });
                } else {
                    // Show error popup for insufficient payment and keep modal open
                    Swal.fire({
                        icon: 'warning',
                        title: 'Uang Tidak Cukup',
                        text: data.message || 'Terjadi kesalahan',
                        confirmButtonColor: '#d33',
                        confirmButtonText: 'OK'
                    });
                    // Modal stays open, no redirect
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Terjadi kesalahan saat memproses pembayaran'
                });
            });
        };
    }

    // Check if we're on the transaction cancel page
    if (document.querySelector('input[name="confirm_batal"]')) {
        // Function to handle cancel form submission
        window.handleCancelSubmit = function(event) {
            event.preventDefault(); // Prevent default form submission

            const checkbox = document.querySelector('input[name="confirm_batal"]');
            if (!checkbox.checked) {
                Swal.fire({
                    title: 'Peringatan!',
                    text: 'Silakan centang konfirmasi pembatalan transaksi terlebih dahulu.',
                    icon: 'warning',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK'
                });
                return false;
            }

            window.confirmCancelTransaction().then((confirmed) => {
                if (confirmed) {
                    event.target.submit(); // Submit the form if confirmed
                }
            });
        };

        // Function to confirm cancel transaction
        window.confirmCancelTransaction = function() {
            return Swal.fire({
                title: 'Apakah Anda yakin?',
                text: 'Transaksi ini akan dibatalkan dan stok barang akan dikembalikan.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Batalkan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                return result.isConfirmed;
            });
        };
    }

    // Check if we're on the return page
    if (document.getElementById('return_0')) {
        // Function to toggle jumlah return
        window.toggleJumlahReturn = function(index) {
            const checkbox = document.getElementById('return_' + index);
            const jumlahInput = document.getElementById('jumlah_return_' + index);
            jumlahInput.disabled = !checkbox.checked;
            if (!checkbox.checked) {
                jumlahInput.value = 0;
            } else {
                jumlahInput.value = 1;
            }
        };
    }

    // Check if we're on the barang index page
    if (document.querySelector('.remove-item-btn')) {
        // Function to confirm delete barang
        window.confirmDeleteBarang = function(id, namaBarang) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: `Barang "${namaBarang}" akan dihapus secara permanen!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        };
    }

    // Success messages for all pages
    if (typeof window.Swal !== 'undefined') {
        // Success message handling will be done in Blade templates
    }
});

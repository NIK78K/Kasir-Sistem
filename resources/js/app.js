import './bootstrap';

// Use globals provided by CDN for lighter bundle
const Swal = window.Swal;



    // Transaction page functionality
    document.addEventListener('DOMContentLoaded', function() {
        // Auto scroll to sections if hash is present
        function autoScrollToSection() {
            if (window.location.hash === '#daftar-belanja') {
                const cartSection = document.getElementById('daftar-belanja');
                if (cartSection) {
                    setTimeout(() => {
                        cartSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }, 100);
                }
            } else if (window.location.hash === '#cari-dan-daftar-barang') {
                const searchSection = document.getElementById('cari-dan-daftar-barang');
                if (searchSection) {
                    setTimeout(() => {
                        searchSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }, 100);
                }
            }
        }

        // Call auto scroll on page load
        autoScrollToSection();

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
                    confirmButtonColor: '#10b981',
                    confirmButtonText: 'OK'
                }).then(() => {
                    // Redirect to confirm page or reload
                    window.location.href = '/transaksi/confirm';
                });
            } else {
                // Show error popup for insufficient payment and keep modal open
                Swal.fire({
                    icon: 'warning',
                    title: 'Telah Terjadi Kesalahan!',
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

// Event delegation for delete buttons and cart actions (works with AJAX-loaded content and Alpine.js DOM manipulation)
// Attach global click handlers only if relevant containers exist (performance guard for non-transaksi pages)
document.addEventListener('click', function(e) {
    // Handle add to cart
    if (document.body.classList.contains('page-transaksi') && e.target.closest('.add-to-cart-btn')) {
        e.preventDefault();
        const button = e.target.closest('.add-to-cart-btn');
        const form = button.closest('form');
        const formData = new FormData(form);

        // Add AJAX header
        const xhr = new XMLHttpRequest();
        xhr.open('POST', form.action, true);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

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
    }

    // Handle barang delete
    if (e.target.closest('.btn-delete-barang')) {
        e.preventDefault();
        const button = e.target.closest('.btn-delete-barang');
        const id = button.dataset.id;
        const namaBarang = button.dataset.nama;

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
                // Show loading
                Swal.fire({
                    title: 'Menghapus...',
                    text: 'Mohon tunggu sebentar',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    willOpen: () => {
                        Swal.showLoading();
                    }
                });

                fetch(`/barang/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    Swal.close();

                    if (data.success) {
                        Swal.fire({
                            title: 'Berhasil!',
                            text: data.message,
                            icon: 'success',
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            // Remove the row from DOM
                            const row = document.querySelector(`#barang-${id}`);
                            if (row) {
                                row.remove();
                            } else {
                                // Fallback: reload the current page content
                                window.location.reload();
                            }
                        });
                    } else {
                        Swal.fire({
                            title: 'Gagal!',
                            text: data.message || 'Terjadi kesalahan saat menghapus barang',
                            icon: 'error',
                            confirmButtonColor: '#d33',
                            confirmButtonText: 'OK'
                        });
                    }
                })
                .catch(error => {
                    Swal.close();
                    console.error('Error:', error);
                    Swal.fire({
                        title: 'Gagal!',
                        text: 'Terjadi kesalahan saat menghapus barang',
                        icon: 'error',
                        confirmButtonColor: '#d33',
                        confirmButtonText: 'OK'
                    });
                });
            }
        });
    }

    // Handle customer delete
    if (e.target.closest('.btn-delete-customer')) {
        e.preventDefault();
        const button = e.target.closest('.btn-delete-customer');
        const id = button.dataset.id;
        const namaCustomer = button.dataset.nama;

        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: `Customer "${namaCustomer}" akan dihapus secara permanen!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading
                Swal.fire({
                    title: 'Menghapus...',
                    text: 'Mohon tunggu sebentar',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    willOpen: () => {
                        Swal.showLoading();
                    }
                });

                fetch(`/customer/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    Swal.close();

                    if (data.success) {
                        Swal.fire({
                            title: 'Berhasil!',
                            text: data.message,
                            icon: 'success',
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            // Remove the row from DOM
                            const row = document.querySelector(`#customer-${id}`);
                            if (row) {
                                row.remove();
                            } else {
                                // Fallback: reload the current page content
                                window.location.reload();
                            }
                        });
                    } else {
                        Swal.fire({
                            title: 'Gagal!',
                            text: data.message || 'Terjadi kesalahan saat menghapus customer',
                            icon: 'error',
                            confirmButtonColor: '#d33',
                            confirmButtonText: 'OK'
                        });
                    }
                })
                .catch(error => {
                    Swal.close();
                    console.error('Error:', error);
                    Swal.fire({
                        title: 'Gagal!',
                        text: 'Terjadi kesalahan saat menghapus customer',
                        icon: 'error',
                        confirmButtonColor: '#d33',
                        confirmButtonText: 'OK'
                    });
                });
            }
        });
    }

    // Handle user delete
    if (e.target.closest('.btn-delete-user')) {
        e.preventDefault();
        const button = e.target.closest('.btn-delete-user');
        const id = button.dataset.id;
        const namaUser = button.dataset.nama;

        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: `User "${namaUser}" akan dihapus secara permanen!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading
                Swal.fire({
                    title: 'Menghapus...',
                    text: 'Mohon tunggu sebentar',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    willOpen: () => {
                        Swal.showLoading();
                    }
                });

                fetch(`/user/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    Swal.close();

                    if (data.success) {
                        Swal.fire({
                            title: 'Berhasil!',
                            text: data.message,
                            icon: 'success',
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            // Remove the row from DOM
                            const row = document.querySelector(`#user-${id}`);
                            if (row) {
                                row.remove();
                            } else {
                                // Fallback: reload the current page content
                                window.location.reload();
                            }
                        });
                    } else {
                        Swal.fire({
                            title: 'Gagal!',
                            text: data.message || 'Terjadi kesalahan saat menghapus user',
                            icon: 'error',
                            confirmButtonColor: '#d33',
                            confirmButtonText: 'OK'
                        });
                    }
                })
                .catch(error => {
                    Swal.close();
                    console.error('Error:', error);
                    Swal.fire({
                        title: 'Gagal!',
                        text: 'Terjadi kesalahan saat menghapus user',
                        icon: 'error',
                        confirmButtonColor: '#d33',
                        confirmButtonText: 'OK'
                    });
                });
            }
        });
    }

    // Handle cart quantity increase
    if (document.body.classList.contains('page-transaksi') && e.target.closest('.btn-increase-qty')) {
        e.preventDefault();
        const button = e.target.closest('.btn-increase-qty');
        const index = button.dataset.index;
        updateCartQuantity(index, 'increase');
    }

    // Handle cart quantity decrease
    if (document.body.classList.contains('page-transaksi') && e.target.closest('.btn-decrease-qty')) {
        e.preventDefault();
        const button = e.target.closest('.btn-decrease-qty');
        const index = button.dataset.index;
        updateCartQuantity(index, 'decrease');
    }

    // Handle cart item remove
    if (document.body.classList.contains('page-transaksi') && e.target.closest('.btn-remove-item')) {
        e.preventDefault();
        const button = e.target.closest('.btn-remove-item');
        const index = button.dataset.index;

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
                // Show loading
                Swal.fire({
                    title: 'Menghapus...',
                    text: 'Mohon tunggu sebentar',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    willOpen: () => {
                        Swal.showLoading();
                    }
                });

                // AJAX delete request
                fetch(`/transaksi/remove-from-cart/${index}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    Swal.close();

                    if (data.success) {
                        Swal.fire({
                            title: 'Berhasil!',
                            text: 'Item berhasil dihapus dari keranjang',
                            icon: 'success',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            // Simpan posisi scroll sebelum reload
                            sessionStorage.setItem('scrollPosition', window.scrollY);
                            
                            // Reload halaman
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            title: 'Gagal!',
                            text: data.message || 'Terjadi kesalahan saat menghapus item',
                            icon: 'error',
                            confirmButtonColor: '#d33',
                            confirmButtonText: 'OK'
                        });
                    }
                })
                .catch(error => {
                    Swal.close();
                    console.error('Error:', error);
                    Swal.fire({
                        title: 'Gagal!',
                        text: 'Terjadi kesalahan saat menghapus item',
                        icon: 'error',
                        confirmButtonColor: '#d33',
                        confirmButtonText: 'OK'
                    });
                });
            }
        });
    }
});

// Function to update cart quantity
function updateCartQuantity(index, action) {
    const xhr = new XMLHttpRequest();
    const url = '/transaksi/update-cart-quantity/' + index;
    xhr.open('POST', url, true);
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
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

// Success messages for all pages
if (typeof window.Swal !== 'undefined') {
    // Success message handling will be done in Blade templates
}
});

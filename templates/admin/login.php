<?php
// Kita gunakan layout yang sama dengan voter untuk hemat waktu
// (Nanti Anda bisa buat layout khusus admin jika mau)
require_once __DIR__ . '/../layout/header.php'; 
?>

<div class="card shadow-sm">
    <div class="card-header bg-dark text-white">
        <h4 class="mb-0">Admin Panel Login</h4>
    </div>
    <div class="card-body">
        <form action="/admin/login" method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-dark w-100">Login</button>
        </form>
    </div>
</div>

<script>
// Menampilkan pesan error
document.addEventListener('DOMContentLoaded', function() {
    const params = new URLSearchParams(window.location.search);
    const error = params.get('error');
    if (error) {
        let message = 'Terjadi kesalahan.';
        if (error === 'wrong' || error === 'empty') {
            message = 'Username atau Password salah.';
        }
        Swal.fire({
            icon: 'error',
            title: 'Login Gagal',
            text: message
        });
    }
});
</script>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
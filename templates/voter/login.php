<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="card shadow-sm">
    <div class="card-header">
        <h4 class="mb-0">Login Pemilih</h4>
    </div>
    <div class="card-body">
        <form action="/login" method="POST">
            <div class="mb-3">
                <label for="nipd" class="form-label">NIM (Nomor Induk Pokok Mahasiswa)</label>
                <input type="text" class="form-control" id="nipd" name="nipd" required autocomplete="username">
            </div>
            <div class="mb-3">
                <label for="tgl_lahir" class="form-label">Password (Tanggal Lahir)</label>
                
                <input type="password" class="form-control" id="tgl_lahir" name="tgl_lahir" required autocomplete="current-password">
                
                <div class="form-text">
                    Ketik tanggal lahir Anda dengan format: <strong>YYYY-MM-DD</strong>
                </div>
            </div>
            <button type="submit" class="btn btn-primary w-100">Login</button>
        </form>
    </div>
</div>

<script>
// Menampilkan pesan error dari URL
document.addEventListener('DOMContentLoaded', function() {
    const params = new URLSearchParams(window.location.search);
    const error = params.get('error');
    if (error) {
        let message = 'Terjadi kesalahan.';
        if (error === 'notfound' || error === 'wrongpass') {
            message = 'NIM atau Password (Tanggal Lahir) salah.';
        } else if (error === 'empty') {
            message = 'NIM dan Password tidak boleh kosong.';
        } else if (error === 'voted') {
            message = 'Anda sudah menggunakan hak suara Anda.';
        } else if (error === 'auth') {
            message = 'Anda harus login terlebih dahulu.';
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
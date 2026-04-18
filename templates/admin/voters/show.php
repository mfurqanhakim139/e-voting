<?php require_once __DIR__ . '/../layout/header.php'; ?>

<h1 class="h3 mb-4">Detail Pemilih</h1>

<div class="row">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="mb-0">Data Mahasiswa</h5>
            </div>
            <div class="card-body">
                <dl class="row">
                    <dt class="col-sm-4">Nama</dt>
                    <dd class="col-sm-8"><?php echo htmlspecialchars($voter['nm_pd']); ?></dd>

                    <dt class="col-sm-4">NIPD</dt>
                    <dd class="col-sm-8"><?php echo htmlspecialchars($voter['nipd']); ?></dd>

                    <dt class="col-sm-4">Prodi</dt>
                    <dd class="col-sm-8"><?php echo htmlspecialchars($voter['alias_prodi']); ?></dd>
                </dl>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="mb-0">Status Pemilihan</h5>
            </div>
            <div class="card-body">
                <?php if ($voter['has_voted']): ?>
                    <div class="alert alert-success">
                        <i class="bi bi-check-circle-fill"></i> <strong>SUDAH MEMILIH</strong>
                    </div>
                    <dl class="row">
                        <dt class="col-sm-4">Waktu</dt>
                        <dd class="col-sm-8"><?php echo $voter['last_voted_at']; ?></dd>
                        
                        <?php if (!empty($vote_history)): ?>
                            <dt class="col-sm-4">Memilih</dt>
                            <dd class="col-sm-8"><?php echo htmlspecialchars($vote_history[0]['name']); ?></dd>
                        <?php endif; ?>
                    </dl>
                    
                    <hr>
                    <p class="text-danger"><strong>Reset Suara:</strong></p>
                    <p>Menekan tombol ini akan menghapus suara pemilih ini dan mengizinkan mereka untuk memilih kembali.</p>
                    <button class="btn btn-danger" onclick="confirmReset(<?php echo $voter['id']; ?>)">
                        <i class="bi bi-arrow-counterclockwise"></i> Reset Suara Pemilih Ini
                    </button>
                    <form id="reset-form-<?php echo $voter['id']; ?>" 
                          action="/admin/voters/<?php echo $voter['id']; ?>/reset" 
                          method="POST" style="display: none;"></form>

                <?php else: ?>
                    <div class="alert alert-secondary">
                        <strong>BELUM MEMILIH</strong>
                    </div>
                    <p>Pemilih ini belum menggunakan hak suaranya.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<a href="/admin/voters" class="btn btn-outline-secondary mt-4">
    <i class="bi bi-arrow-left"></i> Kembali ke Daftar DPT
</a>


<script>
function confirmReset(id) {
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Suara pemilih ini akan dihapus permanen dan statusnya akan dikembalikan menjadi 'Belum Memilih'.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Reset Suara!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            // Sintaks PHP yang salah telah diperbaiki di sini
            document.getElementById('reset-form-' + id).submit();
        }
    });
}
</script>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
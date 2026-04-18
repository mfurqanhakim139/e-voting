<?php require_once __DIR__ . '/../layout/header.php'; ?>

<h1 class="h3 mb-4">Manajemen DPT (Daftar Pemilih Tetap)</h1>

<?php
// Tampilkan pesan flash jika ada (dari proses reset)
if (isset($_SESSION['flash_message'])) {
    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
            ' . $_SESSION['flash_message'] . '
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>';
    unset($_SESSION['flash_message']);
}
?>

<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th scope="col">NIPD</th>
                        <th scope="col">Nama Mahasiswa</th>
                        <th scope="col">Prodi</th>
                        <th scope="col">Status Memilih</th>
                        <th scope="col">Waktu Memilih</th>
                        <th scope="col" width="10%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($voters)): ?>
                        <tr>
                            <td colspan="6" class="text-center">Belum ada data pemilih.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($voters as $voter): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($voter['nipd']); ?></td>
                                <td><?php echo htmlspecialchars($voter['nm_pd']); ?></td>
                                <td><?php echo htmlspecialchars($voter['alias_prodi']); ?></td>
                                <td>
                                    <?php if ($voter['has_voted']): ?>
                                        <span class="badge bg-success"><i class="bi bi-check-circle-fill"></i> Sudah</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Belum</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo $voter['last_voted_at'] ?? '-'; ?></td>
                                <td>
                                    <a href="/admin/voters/<?php echo $voter['id']; ?>" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye-fill"></i> Detail
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
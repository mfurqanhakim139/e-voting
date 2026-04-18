<?php require_once __DIR__ . '/../layout/header.php'; ?>

<h1 class="h3 mb-4">Hasil Pemilihan (Quick Count)</h1>

<div class="card shadow-sm">
    <div class="card-header">
        <h5 class="mb-0">Pilih Event Pemilihan</h5>
    </div>
    <div class="card-body">
        <p>Silakan pilih event pemilihan yang ingin Anda lihat hasilnya.</p>
        
        <?php if (empty($elections)): ?>
            <div class="alert alert-warning">
                Belum ada event pemilihan yang dibuat.
            </div>
        <?php else: ?>
            <div class="list-group">
                <?php foreach ($elections as $election): ?>
                    <a href="/admin/results/<?php echo $election['id']; ?>" 
                       class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1"><?php echo htmlspecialchars($election['name']); ?></h6>
                            <small>
                                Status: 
                                <?php if ($election['status'] == 'active'): ?>
                                    <span class="badge bg-success">Aktif</span>
                                <?php elseif ($election['status'] == 'pending'): ?>
                                    <span class="badge bg-warning text-dark">Pending</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Ditutup</span>
                                <?php endif; ?>
                            </small>
                        </div>
                        <i class="bi bi-chevron-right"></i>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
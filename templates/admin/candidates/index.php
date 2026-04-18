<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3">Manajemen Kandidat</h1>
        <p class="mb-0 text-muted">Untuk Event: <strong><?php echo htmlspecialchars($election['name']); ?></strong></p>
    </div>
    <div>
        <a href="/admin/elections" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Kembali ke Event
        </a>
        <a href="/admin/elections/<?php echo $election['id']; ?>/candidates/new" class="btn btn-primary">
            <i class="bi bi-person-plus-fill"></i> Tambah Kandidat
        </a>
    </div>
</div>

<div class="row">
    <?php if (empty($candidates)): ?>
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center">
                    Belum ada kandidat untuk event ini.
                </div>
            </div>
        </div>
    <?php else: ?>
        <?php foreach ($candidates as $candidate): ?>
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm">
                    <img src="<?php echo htmlspecialchars($candidate['photo_url'] ?? 'https://placehold.co/600x400?text=No+Photo'); ?>" 
                         class="card-img-top" 
                         alt="<?php echo htmlspecialchars($candidate['name']); ?>" 
                         style="height: 200px; object-fit: cover;">
                    <div class="card-body">
                        <h4 class="card-title">
                            <span class="badge bg-dark me-2"><?php echo $candidate['nomor_urut']; ?></span>
                            <?php echo htmlspecialchars($candidate['name']); ?>
                        </h4>
                        <p class="card-text text-muted">
                            <strong>Visi:</strong> <?php echo substr(htmlspecialchars($candidate['visi'] ?? '-'), 0, 50); ?>...
                        </p>
                        
                        <a href="/admin/elections/<?php echo $election['id']; ?>/candidates/<?php echo $candidate['id']; ?>/edit" 
                           class="btn btn-sm btn-primary">
                           <i class="bi bi-pencil-fill"></i> Edit
                        </a>
                        
                        <button class="btn btn-sm btn-danger" onclick="confirmDelete(<?php echo $candidate['id']; ?>)">
                            <i class="bi bi-trash-fill"></i> Hapus
                        </button>
                        
                        <form id="delete-form-<?php echo $candidate['id']; ?>" 
                              action="/admin/elections/<?php echo $election['id']; ?>/candidates/<?php echo $candidate['id']; ?>/delete" 
                              method="POST" style="display: none;"></form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<script>
function confirmDelete(id) {
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Kandidat ini akan dihapus. Ini bisa memengaruhi data suara jika pemilihan sudah berjalan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-form-' + id).submit();
        }
    });
}
</script>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
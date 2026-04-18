<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3">Manajemen Event Pemilihan</h1>
    <a href="/admin/elections/new" class="btn btn-primary">
        <i class="bi bi-plus-circle-fill"></i> Tambah Event Baru
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Nama Event</th>
                        <th scope="col">Status</th>
                        <th scope="col">Waktu Mulai</th>
                        <th scope="col">Waktu Selesai</th>
                        <th scope="col" width="20%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($elections)): ?>
                        <tr>
                            <td colspan="6" class="text-center">Belum ada event pemilihan.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($elections as $election): ?>
                            <tr>
                                <th scope="row"><?php echo $election['id']; ?></th>
                                <td><?php echo htmlspecialchars($election['name']); ?></td>
                                <td>
                                    <?php if ($election['status'] == 'active'): ?>
                                        <span class="badge bg-success">Aktif</span>
                                    <?php elseif ($election['status'] == 'pending'): ?>
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Ditutup</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo $election['start_time'] ?? '-'; ?></td>
                                <td><?php echo $election['end_time'] ?? '-'; ?></td>
                                <td>
                                    <a href="/admin/elections/<?php echo $election['id']; ?>/candidates" class="btn btn-sm btn-info">
                                        <i class="bi bi-person-badge-fill"></i> Kandidat
                                    </a>
                                    
                                    <a href="/admin/elections/<?php echo $election['id']; ?>/edit" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil-fill"></i> Edit
                                    </a>
                                    
                                    <button class="btn btn-sm btn-outline-danger" onclick="confirmDelete(<?php echo $election['id']; ?>)">
                                        <i class="bi bi-trash-fill"></i> Hapus
                                    </button>
                                    <form id="delete-form-<?php echo $election['id']; ?>" action="/admin/elections/<?php echo $election['id']; ?>/delete" method="POST" style="display: none;"></form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function confirmDelete(id) {
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Event ini akan dihapus. Kandidat dan Suara yang terkait juga akan terhapus!",
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
<?php require_once __DIR__ . '/../layout/header.php'; ?>

<?php
// Tentukan apakah ini mode 'Edit' atau 'Tambah Baru'
$is_edit = isset($election['id']);
$form_action = $is_edit ? '/admin/elections/' . $election['id'] : '/admin/elections';
$page_title = $is_edit ? 'Edit Event: ' . htmlspecialchars($election['name']) : 'Tambah Event Baru';
?>

<h1 class="h3 mb-4"><?php echo $page_title; ?></h1>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="<?php echo $form_action; ?>" method="POST">
            
            <div class="mb-3">
                <label for="name" class="form-label">Nama Event</label>
                <input type="text" class="form-control" id="name" name="name" 
                       value="<?php echo htmlspecialchars($election['name'] ?? ''); ?>" required>
            </div>

            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" id="status" name="status" required>
                    <option value="pending" <?php echo ($election['status'] ?? '') == 'pending' ? 'selected' : ''; ?>>Pending</option>
                    <option value="active" <?php echo ($election['status'] ?? '') == 'active' ? 'selected' : ''; ?>>Active</option>
                    <option value="closed" <?php echo ($election['status'] ?? '') == 'closed' ? 'selected' : ''; ?>>Closed</option>
                </select>
                <div class="form-text">Hanya event dengan status "Active" yang akan muncul di halaman pemilih.</div>
            </div>

            <div class="mb-3">
                <label for="start_time" class="form-label">Waktu Mulai (Opsional)</label>
                <input type="datetime-local" class="form-control" id="start_time" name="start_time"
                       value="<?php echo htmlspecialchars($election['start_time'] ?? ''); ?>">
            </div>

            <div class="mb-3">
                <label for="end_time" class="form-label">Waktu Selesai (Opsional)</label>
                <input type="datetime-local" class="form-control" id="end_time" name="end_time"
                       value="<?php echo htmlspecialchars($election['end_time'] ?? ''); ?>">
            </div>

            <hr>

            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="/admin/elections" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
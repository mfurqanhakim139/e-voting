<?php require_once __DIR__ . '/../layout/header.php'; ?>

<?php
// Tentukan mode
$is_edit = isset($candidate['id']);
$form_action = $is_edit 
    ? '/admin/elections/' . $election['id'] . '/candidates/' . $candidate['id'] 
    : '/admin/elections/' . $election['id'] . '/candidates';
$page_title = $is_edit ? 'Edit Kandidat' : 'Tambah Kandidat Baru';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3"><?php echo $page_title; ?></h1>
        <p class="mb-0 text-muted">Untuk Event: <strong><?php echo htmlspecialchars($election['name']); ?></strong></p>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="<?php echo $form_action; ?>" method="POST" enctype="multipart/form-data">
            
            <div class="mb-3">
                <label for="nomor_urut" class="form-label">Nomor Urut</label>
                <input type="number" class="form-control" id="nomor_urut" name="nomor_urut" 
                       value="<?php echo htmlspecialchars($candidate['nomor_urut'] ?? ''); ?>" required>
            </div>

            <div class="mb-3">
                <label for="name" class="form-label">Nama Kandidat</label>
                <input type="text" class="form-control" id="name" name="name" 
                       value="<?php echo htmlspecialchars($candidate['name'] ?? ''); ?>" required>
                <div class="form-text">Contoh: "Budi & Susi" atau "Ahmad"</div>
            </div>

            <div class="mb-3">
                <label for="photo" class="form-label">Foto Kandidat</label>
                <input type="file" class="form-control" id="photo" name="photo" accept="image/jpeg, image/png">
                <?php if ($is_edit && !empty($candidate['photo_url'])): ?>
                    <div class="mt-2">
                        <img src="<?php echo htmlspecialchars($candidate['photo_url']); ?>" alt="Foto saat ini" style="width: 150px; height: auto;">
                        <input type="hidden" name="old_photo_url" value="<?php echo htmlspecialchars($candidate['photo_url']); ?>">
                        <div class="form-text">Upload file baru untuk mengganti foto ini.</div>
                    </div>
                <?php else: ?>
                    <div class="form-text">File JPG atau PNG.</div>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label for="visi" class="form-label">Visi</label>
                <textarea class="form-control" id="visi" name="visi" rows="3"><?php echo htmlspecialchars($candidate['visi'] ?? ''); ?></textarea>
            </div>

            <div class="mb-3">
                <label for="misi" class="form-label">Misi</label>
                <textarea class="form-control" id="misi" name="misi" rows="5"><?php echo htmlspecialchars($candidate['misi'] ?? ''); ?></textarea>
            </div>

            <hr>

            <button type="submit" class="btn btn-primary">Simpan Kandidat</button>
            <a href="/admin/elections/<?php echo $election['id']; ?>/candidates" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
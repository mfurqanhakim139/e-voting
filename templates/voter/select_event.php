<?php
// Variabel $elections, $nama_pemilih, $nipd_pemilih
// didapat dari VoteController::showEventSelectionPage()
?>

<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="card shadow-sm">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Pilih Event Pemilihan</h4>
            <a href="/logout" class="btn btn-sm btn-outline-danger">Logout</a>
        </div>
    </div>
    <div class="card-body">
        <div class="text-center mb-4">
            <p class="lead mb-1">Selamat datang, <strong><?php echo $nama_pemilih; ?></strong>!</p>
            <p>Silakan pilih salah satu event pemilihan yang aktif di bawah ini untuk memberikan suara Anda.</p>
        </div>

        <?php
        // Tampilkan pesan error jika sudah vote
        if (isset($_GET['error']) && $_GET['error'] == 'alreadyvoted') {
            echo '<div class="alert alert-danger"><i class="bi bi-exclamation-triangle-fill"></i> Anda sudah menggunakan hak suara Anda pada event tersebut.</div>';
        }
        // Tampilkan pesan error jika event ditutup saat akan memilih
        if (isset($_GET['error']) && $_GET['error'] == 'event_closed') {
            echo '<div class="alert alert-warning">Event pemilihan tersebut baru saja ditutup.</div>';
        }
        ?>

        <div class="list-group">
            <?php foreach ($elections as $election): ?>
                <a href="/vote/<?php echo $election['id']; ?>" 
                   class="list-group-item list-group-item-action p-3">
                    <div class="d-flex w-100 justify-content-between">
                        <h5 class="mb-1"><?php echo htmlspecialchars($election['name']); ?></h5>
                        <i class="bi bi-chevron-right fs-5"></i>
                    </div>
                    <small>Status: <span class="badge bg-success">Aktif</span></small>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
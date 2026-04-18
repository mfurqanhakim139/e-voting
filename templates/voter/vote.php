<?php
// CATATAN:
// SEMUA DATA DUMMY DI ATAS (array $election, $candidates) SUDAH DIHAPUS.
// Keamanan (session_start(), pengecekan login) juga sudah dipindah.
//
// Variabel $election, $candidates, $nama_pemilih, dan $nipd_pemilih
// sekarang otomatis didapat dari 'VoteController.php'.
?>

<?php require_once __DIR__ . '/../layout/header.php'; ?>

</div>
</div>
<div class="row justify-content-center">
    <div class="col-md-10"> <div class="text-center mb-4">
            <h3 class="mb-1"><?php echo $election['name']; ?></h3>
            <p class="lead">Selamat datang, <strong><?php echo $nama_pemilih; ?></strong> (<?php echo $nipd_pemilih; ?>)</p>
            <p>Silakan gunakan hak pilih Anda dengan memilih salah satu kandidat di bawah ini. Pilihan Anda bersifat rahasia.</p>
            <a href="/logout" class="btn btn-sm btn-outline-danger">Logout</a>
            <hr>
        </div>

        <div class="row g-4">
            <?php foreach ($candidates as $candidate): ?>
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm">
                        <img src="<?php echo $candidate['photo_url']; ?>" class="card-img-top" alt="<?php echo $candidate['name']; ?>">
                        <div class="card-body text-center">
                            <h4 class="card-title"><?php echo $candidate['name']; ?></h4>
                            <p class="card-text display-6">
                                <strong><?php echo $candidate['nomor_urut']; ?></strong>
                            </p>
                        </div>
                        <div class="card-footer p-3">
                            <div class="d-grid gap-2">
                                <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modal-<?php echo $candidate['id']; ?>">
                                    Lihat Visi & Misi
                                </button>
                                
                                <button class="btn btn-success btn-lg vote-btn" 
                                        data-candidate-id="<?php echo $candidate['id']; ?>"
                                        data-candidate-name="<?php echo htmlspecialchars($candidate['name']); ?>">
                                    <strong>PILIH KANDIDAT INI</strong>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="modal-<?php echo $candidate['id']; ?>" tabindex="-1" aria-labelledby="modalLabel-<?php echo $candidate['id']; ?>" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-scrollable modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalLabel-<?php echo $candidate['id']; ?>">Visi & Misi - <?php echo $candidate['name']; ?></h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <h5>Visi</h5>
                                <p><?php echo htmlspecialchars($candidate['visi']); ?></p>
                                <hr>
                                <h5>Misi</h5>
                                <p style="white-space: pre-line;"><?php echo htmlspecialchars($candidate['misi']); ?></p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<form id="voteForm" action="/vote/submit" method="POST" style="display: none;">
    <input type="hidden" name="election_id" value="<?php echo $election['id']; ?>">
    <input type="hidden" id="candidate_id_input" name="candidate_id" value="">
</form>

<div class="row justify-content-center">
    <div class="col-md-6">
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const params = new URLSearchParams(window.location.search);
            const error = params.get('error');
            if (error === 'alreadyvoted') {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Anda sudah menggunakan hak suara Anda untuk pemilihan ini.'
                });
            }
        });
        </script>
        
        <?php require_once __DIR__ . '/../layout/footer.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const voteButtons = document.querySelectorAll('.vote-btn');
    voteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const candidateId = this.dataset.candidateId;
            const candidateName = this.dataset.candidateName;
            
            Swal.fire({
                title: 'Konfirmasi Pilihan Anda',
                text: "Apakah Anda yakin ingin memilih " + candidateName + "? Pilihan tidak dapat diubah setelah dikonfirmasi.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Saya Yakin!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('candidate_id_input').value = candidateId;
                    document.getElementById('voteForm').submit();
                }
            });
        });
    });
});
</script>
<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3"><?php echo htmlspecialchars($data['election']['name']); ?></h1>
        <p class="mb-0 text-muted">Hasil Suara Real-time</p>
    </div>
    <div>
        <a href="/admin/results" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Ganti Event
        </a>
        <a href="/admin/results/<?php echo $data['election']['id']; ?>/print" target="_blank" class="btn btn-primary">
            <i class="bi bi-printer-fill"></i> Cetak Laporan
        </a>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <h6 class="text-uppercase text-primary">Total Suara Masuk</h6>
                <h1 class="display-4"><?php echo $data['totalVotes']; ?></h1>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <h6 class="text-uppercase text-success">Partisipasi Pemilih</h6>
                <h1 class="display-4">
                    <?php 
                        $partisipasi = ($data['totalVoters'] > 0) ? ($data['totalVotes'] / $data['totalVoters']) * 100 : 0;
                        echo round($partisipasi, 1) . '%';
                    ?>
                </h1>
                <small><?php echo $data['totalVotes']; ?> dari <?php echo $data['totalVoters']; ?> DPT</small>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-7">
        <div class="card shadow-sm">
            <div class="card-header">
                Grafik Perolehan Suara
            </div>
            <div class="card-body">
                <canvas id="voteChart"></canvas>
            </div>
        </div>
    </div>
    
    <div class="col-md-5">
        <div class="card shadow-sm">
            <div class="card-header">
                Rincian Suara
            </div>
            <div class="card-body p-0">
                <table class="table table-striped table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Kandidat</th>
                            <th class="text-end">Suara</th>
                            <th class="text-end">%</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data['results'] as $row): ?>
                            <tr>
                                <td>
                                    <strong>(<?php echo $row['nomor_urut']; ?>)</strong>
                                    <?php echo htmlspecialchars($row['name']); ?>
                                </td>
                                <td class="text-end"><strong><?php echo $row['vote_count']; ?></strong></td>
                                <td class="text-end"><?php echo $row['percentage']; ?>%</td>
                            </tr>
                        <?php endforeach; ?>
                        <tr class="table-dark">
                            <td><strong>TOTAL</strong></td>
                            <td class="text-end"><strong><?php echo $data['totalVotes']; ?></strong></td>
                            <td class="text-end">100%</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('voteChart').getContext('2d');
    
    // Ambil data JSON dari PHP
    const labels = <?php echo $data['chartLabels']; ?>;
    const voteData = <?php echo $data['chartData']; ?>;

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Jumlah Suara',
                data: voteData,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.7)',
                    'rgba(54, 162, 235, 0.7)',
                    'rgba(255, 206, 86, 0.7)',
                    'rgba(75, 192, 192, 0.7)',
                    'rgba(153, 102, 255, 0.7)',
                    'rgba(255, 159, 64, 0.7)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        // Pastikan hanya angka bulat (integer) di sumbu Y
                        precision: 0
                    }
                }
            },
            plugins: {
                legend: {
                    display: false // Sembunyikan legenda
                }
            }
        }
    });
});
</script>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
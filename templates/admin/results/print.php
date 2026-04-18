<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Laporan - <?php echo htmlspecialchars($data['election']['name']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @media print {
            body { -webkit-print-color-adjust: exact; }
            .no-print { display: none; }
        }
        body { background-color: #fff; }
        .container { max-width: 900px; }
        .header { text-align: center; border-bottom: 3px double #000; padding-bottom: 10px; margin-bottom: 20px; }
        /*  */
    </style>
</head>
<body onload="window.print()">

<div class="container mt-4">
    <button class="btn btn-primary no-print" onclick="window.print()">
        <i class="bi bi-printer-fill"></i> Cetak Ulang
    </button>
    
    <div class="header mt-3">
        <h4>UNIVERSITAS GRAHA KARYA MUARA BULIAN</h4>
        <h5>PANITIA PEMILIHAN RAYA (KPUM)</h5>
        <h5 class="mt-3">LAPORAN AKHIR HASIL PEROLEHAN SUARA</h5>
    </div>

    <h5>Event: <?php echo htmlspecialchars($data['election']['name']); ?></h5>
    <p>
        Tanggal Cetak: <?php echo date('d M Y, H:i:s'); ?>
    </p>
    
    <hr>
    
    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th scope="col" width="10%">No. Urut</th>
                <th scope="col">Nama Kandidat</th>
                <th scope="col" class="text-end" width="20%">Jumlah Suara</th>
                <th scope="col" class="text-end" width="20%">Persentase (%)</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($data['results'] as $row): ?>
                <tr>
                    <td class="text-center"><strong><?php echo $row['nomor_urut']; ?></strong></td>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td class="text-end"><?php echo $row['vote_count']; ?></td>
                    <td class="text-end"><?php echo $row['percentage']; ?>%</td>
                </tr>
            <?php endforeach; ?>
            <tr class->
                <td colspan="2" class="text-end"><strong>TOTAL SUARA SAH</strong></td>
                <td class="text-end"><strong><?php echo $data['totalVotes']; ?></strong></td>
                <td class="text-end">100%</td>
            </tr>
        </tbody>
    </table>
    
    <div class="row mt-4">
        <div class="col-6">
            <p>Total DPT: <strong><?php echo $data['totalVoters']; ?></strong></p>
            <p>Total Suara Masuk: <strong><?php echo $data['totalVotes']; ?></strong></p>
            <p>Tingkat Partisipasi: <strong><?php echo round(($data['totalVoters'] > 0) ? ($data['totalVotes'] / $data['totalVoters']) * 100 : 0, 1); ?>%</strong></p>
        </div>
        <div class="col-6 text-center">
            <p>Muara Bulian, <?php echo date('d F Y'); ?></p>
            <p>Mengetahui,</p>
            <br><br><br>
            <p>(Ketua KPUM)</p>
        </div>
    </div>

</div>

</body>
</html>
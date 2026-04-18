<?php require_once __DIR__ . '/layout/header.php'; ?>

<h1 class="h3 mb-4">Dashboard</h1>

<?php if (isset($error_message)): ?>
    <div class="alert alert-danger">
        Gagal memuat statistik: <?php echo htmlspecialchars($error_message); ?>
    </div>
<?php endif; ?>

<div class="row">
    <div class="col-md-3 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total Pemilih (DPT)</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $voterCount; ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-people-fill fs-2 text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Total Suara Masuk</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $voteCount; ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-box-arrow-in-right fs-2 text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Total Event</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $electionCount; ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-calendar-event-fill fs-2 text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Total Kandidat</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $candidateCount; ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-person-badge-fill fs-2 text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/layout/footer.php'; ?>
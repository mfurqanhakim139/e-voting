<?php
// Ambil nama admin dari session
$admin_name = $_SESSION['admin_name'] ?? 'Admin';

// Ambil path URL saat ini untuk menandai link aktif
$current_page = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Fungsi helper untuk mengecek apakah link aktif
function isAdminPageActive($page, $current_page) {
    // Penanganan khusus untuk /admin dan /admin/dashboard
    if (($page == '/admin/dashboard' || $page == '/admin') && ($current_page == '/admin' || $current_page == '/admin/dashboard')) {
        return 'active';
    }
    // Hapus kondisi di atas jika /admin bukan alias dashboard
    
    // Penanganan umum
    if ($page != '/admin/dashboard' && $page != '/admin') {
         return strpos($current_page, $page) === 0 ? 'active' : '';
    }
    
    return '';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel E-Voting</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            display: flex;
            min-height: 100vh;
            flex-direction: column;
            background-color: #f8f9fa;
        }
        .sidebar {
            width: 280px;
            background-color: #343a40;
            color: white;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            padding-top: 20px;
        }
        .sidebar .nav-link {
            color: #cda;
        }
        .sidebar .nav-link.active {
            color: #fff;
            background-color: #495057;
        }
        .content {
            margin-left: 280px;
            padding: 20px;
            width: 100%;
        }
        .navbar-admin {
            width: calc(100% - 280px);
            margin-left: 280px;
        }
    </style>
</head>
<body>

<div class="sidebar d-flex flex-column p-3">
    <h4><i class="bi bi-person-shield"></i> Admin E-Voting</h4>
    <hr>
    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item">
            <a href="/admin/dashboard" class="nav-link <?php echo isAdminPageActive('/admin/dashboard', $current_page); ?>">
                <i class="bi bi-grid-fill"></i> Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a href="/admin/elections" class="nav-link <?php echo isAdminPageActive('/admin/elections', $current_page); ?>">
                <i class="bi bi-calendar-event-fill"></i> Manajemen Event
            </a>
        </li>
        <li class="nav-item">
            <a href="/admin/voters" class="nav-link <?php echo isAdminPageActive('/admin/voters', $current_page); ?>">
                <i class="bi bi-people-fill"></i> Manajemen DPT
            </a>
        </li>
         <li class="nav-item">
            <a href="/admin/results" class="nav-link <?php echo isAdminPageActive('/admin/results', $current_page); ?>">
                <i class="bi bi-bar-chart-line-fill"></i> Hasil Suara
            </a>
        </li>
    </ul>
    <hr>
    <div class="dropdown">
        <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-person-circle me-2"></i>
            <strong><?php echo htmlspecialchars($admin_name); ?></strong>
        </a>
        <ul class="dropdown-menu dropdown-menu-dark text-small shadow">
            <li><a class="dropdown-item" href="/admin/logout">Sign out</a></li>
        </ul>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
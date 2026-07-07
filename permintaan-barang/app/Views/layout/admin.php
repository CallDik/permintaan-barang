<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Admin' ?> - Sistem Permintaan Barang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background: #f0f2f5; }
        .sidebar {
            width: 260px;
            min-height: 100vh;
            background: linear-gradient(180deg, #1e3c72 0%, #2a5298 100%);
            position: fixed;
            top: 0; left: 0;
            z-index: 100;
            box-shadow: 4px 0 15px rgba(0,0,0,0.2);
        }
        .sidebar-brand {
            padding: 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            text-align: center;
        }
        .sidebar-brand h5 { color: white; font-weight: 700; font-size: 14px; margin: 8px 0 0; }
        .sidebar-brand small { color: rgba(255,255,255,0.6); font-size: 11px; }
        .sidebar-brand i { font-size: 32px; color: #ffd700; }
        .nav-label {
            color: rgba(255,255,255,0.4);
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            padding: 16px 20px 6px;
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,0.75);
            padding: 10px 20px;
            border-radius: 8px;
            margin: 2px 10px;
            font-size: 14px;
            transition: all 0.2s;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background: rgba(255,255,255,0.15);
            color: white;
        }
        .sidebar .nav-link i { width: 20px; margin-right: 8px; }
        .main-content { margin-left: 260px; }
        .topbar {
            background: white;
            padding: 12px 24px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 99;
        }
        .topbar h6 { margin: 0; font-weight: 600; color: #333; }
        .content-area { padding: 24px; }
        .badge-pending {
            background: #dc3545;
            color: white;
            border-radius: 50px;
            padding: 2px 7px;
            font-size: 11px;
            font-weight: 600;
        }
        .card { border: none; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,0.06); }
        .card-header { border-radius: 12px 12px 0 0 !important; font-weight: 600; }
        .btn { border-radius: 8px; }
        .table th { font-size: 13px; font-weight: 600; }
        .table td { font-size: 13px; vertical-align: middle; }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-brand">
            <i class="fas fa-box-open"></i>
            <h5>Sistem Permintaan</h5>
            <small>Panel Admin</small>
        </div>
        <nav class="mt-2">
            <div class="nav-label">Menu Utama</div>
            <a href="/admin/dashboard" class="nav-link <?= (uri_string() === 'admin/dashboard') ? 'active' : '' ?>">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
            <a href="/admin/permintaan" class="nav-link <?= (strpos(uri_string(), 'admin/permintaan') !== false) ? 'active' : '' ?>">
                <i class="fas fa-clipboard-list"></i> Permintaan
                <?php if (isset($pending) && $pending > 0): ?>
                    <span class="badge-pending ms-1"><?= $pending ?></span>
                <?php endif; ?>
            </a>
            <a href="/admin/karyawan" class="nav-link <?= (strpos(uri_string(), 'admin/karyawan') !== false) ? 'active' : '' ?>">
                <i class="fas fa-users"></i> Karyawan
            </a>
            <a href="/admin/master-alat-kantor" class="nav-link <?= (strpos(uri_string(), 'master-alat-kantor') !== false) ? 'active' : '' ?>">
                <i class="fas fa-boxes"></i> Data Stok
            </a>
            <div class="nav-label mt-2">Akun</div>
            <a href="/logout" class="nav-link text-danger">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="topbar">
            <h6><?= $title ?? 'Dashboard' ?></h6>
            <div class="d-flex align-items-center gap-3">
                <?php if (isset($pending) && $pending > 0): ?>
                    <span class="text-danger small">
                        <i class="fas fa-bell"></i>
                        <strong><?= $pending ?></strong> permintaan menunggu validasi
                    </span>
                <?php endif; ?>
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center" style="width:34px;height:34px;">
                        <i class="fas fa-user text-white" style="font-size:14px;"></i>
                    </div>
                    <div>
                        <div class="fw-semibold" style="font-size:13px;"><?= session()->get('nama') ?></div>
                        <div class="text-muted" style="font-size:11px;">Administrator</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-area">
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="fas fa-check-circle me-2"></i><?= session()->getFlashdata('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="fas fa-exclamation-circle me-2"></i><?= session()->getFlashdata('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            <?= $this->renderSection('content') ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <?= $this->renderSection('scripts') ?>
</body>
</html>

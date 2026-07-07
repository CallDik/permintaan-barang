<?= $this->extend('layout/admin') ?>
<?= $this->section('content') ?>

<!-- Statistik Permintaan -->
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card border-start border-warning border-4">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-warning bg-opacity-15 p-3">
                    <i class="fas fa-clock text-warning fa-lg"></i>
                </div>
                <div>
                    <div class="text-muted small">Pending</div>
                    <div class="fw-bold fs-4"><?= $total_pending ?></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-start border-success border-4">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-success bg-opacity-15 p-3">
                    <i class="fas fa-check-circle text-success fa-lg"></i>
                </div>
                <div>
                    <div class="text-muted small">Disetujui</div>
                    <div class="fw-bold fs-4"><?= $total_valid ?></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-start border-danger border-4">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-danger bg-opacity-15 p-3">
                    <i class="fas fa-times-circle text-danger fa-lg"></i>
                </div>
                <div>
                    <div class="text-muted small">Ditolak</div>
                    <div class="fw-bold fs-4"><?= $total_ditolak ?></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-start border-primary border-4">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-primary bg-opacity-15 p-3">
                    <i class="fas fa-users text-primary fa-lg"></i>
                </div>
                <div>
                    <div class="text-muted small">Total Karyawan</div>
                    <div class="fw-bold fs-4"><?= $total_karyawan ?></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Statistik Alat Kantor -->
<div class="card mb-4">
    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
        <span><i class="fas fa-clipboard-list me-2"></i>Ringkasan Stok Alat Kantor</span>
        <a href="/admin/master-alat-kantor" class="btn btn-sm btn-light">Kelola Master</a>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-3">
                <div class="p-3 rounded bg-primary bg-opacity-10 text-center">
                    <div class="fw-bold fs-3 text-primary"><?= $total_jenis_barang ?></div>
                    <div class="text-muted small">Jenis Barang</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-3 rounded bg-success bg-opacity-10 text-center">
                    <div class="fw-bold fs-3 text-success"><?= number_format($total_stok_kantor) ?></div>
                    <div class="text-muted small">Total Stok</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-3 rounded bg-warning bg-opacity-10 text-center">
                    <div class="fw-bold fs-3 text-warning"><?= $jumlah_hampir_habis ?></div>
                    <div class="text-muted small">Hampir Habis (&lt;10)</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="p-3 rounded bg-danger bg-opacity-10 text-center">
                    <div class="fw-bold fs-3 text-danger"><?= $jumlah_habis ?></div>
                    <div class="text-muted small">Stok Habis</div>
                </div>
            </div>
        </div>

        <!-- Peringatan barang hampir habis -->
        <?php if (!empty($barang_hampir_habis) || !empty($barang_habis)): ?>
        <hr>
        <div class="row g-3">
            <?php if (!empty($barang_habis)): ?>
            <div class="col-md-6">
                <h6 class="text-danger fw-bold"><i class="fas fa-times-circle me-1"></i>Stok Habis</h6>
                <ul class="list-group list-group-flush">
                    <?php foreach ($barang_habis as $b): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-1">
                        <span><?= esc($b['nama_barang']) ?></span>
                        <span class="badge bg-danger">0</span>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>
            <?php if (!empty($barang_hampir_habis)): ?>
            <div class="col-md-6">
                <h6 class="text-warning fw-bold"><i class="fas fa-exclamation-triangle me-1"></i>Hampir Habis</h6>
                <ul class="list-group list-group-flush">
                    <?php foreach ($barang_hampir_habis as $b): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-1">
                        <span><?= esc($b['nama_barang']) ?></span>
                        <span class="badge bg-warning text-dark"><?= $b['stok'] ?></span>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php if ($total_pending > 0): ?>
<div class="alert alert-warning d-flex align-items-center gap-2">
    <i class="fas fa-bell fa-lg"></i>
    <span>Anda memiliki <strong><?= $total_pending ?></strong> permintaan yang menunggu validasi.</span>
    <a href="/admin/permintaan?status=Pending" class="btn btn-warning btn-sm ms-auto">Lihat Sekarang</a>
</div>
<?php endif; ?>

<div class="card">
    <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
        <span><i class="fas fa-clock me-2"></i>Permintaan Terbaru (Pending)</span>
        <a href="/admin/permintaan" class="btn btn-sm btn-dark">Lihat Semua</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Kode</th>
                        <th>Karyawan</th>
                        <th>Nama Permintaan</th>
                        <th>Kategori</th>
                        <th>Jumlah</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($permintaan_baru)): ?>
                        <tr><td colspan="7" class="text-center text-muted py-4">Tidak ada permintaan pending</td></tr>
                    <?php else: ?>
                        <?php foreach ($permintaan_baru as $p): ?>
                        <tr>
                            <td><code><?= $p['kode_permintaan'] ?></code></td>
                            <td><?= $p['nama_karyawan'] ?></td>
                            <td><?= $p['nama_permintaan'] ?></td>
                            <td><span class="badge bg-secondary"><?= $p['kategori'] ?></span></td>
                            <td><?= $p['jumlah'] ?></td>
                            <td><?= date('d/m/Y', strtotime($p['created_at'])) ?></td>
                            <td>
                                <a href="/admin/permintaan/detail/<?= $p['id'] ?>" class="btn btn-sm btn-primary">
                                    <i class="fas fa-eye"></i> Detail
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

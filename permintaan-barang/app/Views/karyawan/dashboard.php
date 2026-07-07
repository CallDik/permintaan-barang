<?= $this->extend('layout/karyawan') ?>
<?= $this->section('content') ?>

<div class="row g-4 mb-4">
    <div class="col-md-4">
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
    <div class="col-md-4">
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
    <div class="col-md-4">
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
</div>

<div class="card">
    <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
        <span><i class="fas fa-history me-2"></i>Permintaan Terbaru Saya</span>
        <a href="/karyawan/permintaan/tambah" class="btn btn-light btn-sm">
            <i class="fas fa-plus me-1"></i>Buat Permintaan
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Kode</th>
                        <th>Nama Permintaan</th>
                        <th>Kategori</th>
                        <th>Jumlah</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($riwayat)): ?>
                        <tr><td colspan="6" class="text-center text-muted py-4">Belum ada permintaan</td></tr>
                    <?php else: ?>
                        <?php foreach (array_slice($riwayat, 0, 5) as $r): ?>
                        <tr>
                            <td><code><?= $r['kode_permintaan'] ?></code></td>
                            <td><?= $r['nama_permintaan'] ?></td>
                            <td><span class="badge bg-secondary"><?= $r['kategori'] ?></span></td>
                            <td><?= $r['jumlah'] ?></td>
                            <td>
                                <?php if ($r['status'] === 'Pending'): ?>
                                    <span class="badge bg-warning text-dark">Pending</span>
                                <?php elseif ($r['status'] === 'Valid'): ?>
                                    <span class="badge bg-success">Valid</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Ditolak</span>
                                <?php endif; ?>
                            </td>
                            <td><?= date('d/m/Y', strtotime($r['created_at'])) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

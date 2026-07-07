<?= $this->extend('layout/karyawan') ?>
<?= $this->section('content') ?>

<div class="card">
    <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
        <span><i class="fas fa-history me-2"></i>Riwayat Permintaan</span>
        <a href="/karyawan/permintaan/tambah" class="btn btn-light btn-sm">
            <i class="fas fa-plus me-1"></i>Buat Permintaan
        </a>
    </div>
    <div class="card-body">
        <!-- Filter -->
        <form method="get" class="d-flex gap-2 mb-4">
            <select name="status" class="form-select w-auto">
                <option value="semua" <?= $status === 'semua' ? 'selected' : '' ?>>Semua Status</option>
                <option value="Pending" <?= $status === 'Pending' ? 'selected' : '' ?>>Pending</option>
                <option value="Valid" <?= $status === 'Valid' ? 'selected' : '' ?>>Valid</option>
                <option value="Ditolak" <?= $status === 'Ditolak' ? 'selected' : '' ?>>Ditolak</option>
            </select>
            <button type="submit" class="btn btn-success">Filter</button>
            <a href="/karyawan/permintaan" class="btn btn-secondary">Reset</a>
        </form>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Kode</th>
                        <th>Nama Permintaan</th>
                        <th>Kategori</th>
                        <th>Jumlah</th>
                        <th>Status</th>
                        <th>Masa Tunggu</th>
                        <th>Keterangan Admin</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($permintaan)): ?>
                        <tr><td colspan="9" class="text-center text-muted py-4">Belum ada data permintaan</td></tr>
                    <?php else: ?>
                        <?php $no = 1; foreach ($permintaan as $p): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><code><?= $p['kode_permintaan'] ?></code></td>
                            <td><?= $p['nama_permintaan'] ?></td>
                            <td><span class="badge bg-secondary"><?= $p['kategori'] ?></span></td>
                            <td><?= $p['jumlah'] ?></td>
                            <td>
                                <?php if ($p['status'] === 'Pending'): ?>
                                    <span class="badge bg-warning text-dark">Pending</span>
                                <?php elseif ($p['status'] === 'Valid'): ?>
                                    <span class="badge bg-success">Valid</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Ditolak</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($p['status'] === 'Ditolak'): ?>
                                    <span class="text-muted small">-</span>
                                <?php elseif (!empty($p['masa_tunggu']) && !empty($p['masa_tunggu']['selesai'])): ?>
                                    <span class="badge bg-success">
                                        <i class="fas fa-check me-1"></i>Selesai
                                    </span>
                                    <div class="text-muted small mt-1">Bebas sejak <?= $p['masa_tunggu']['tanggal_bisa'] ?></div>
                                <?php elseif (!empty($p['masa_tunggu'])): ?>
                                    <span class="badge bg-warning text-dark">
                                        <i class="fas fa-clock me-1"></i><?= $p['masa_tunggu']['sisa_hari'] ?> hari lagi
                                    </span>
                                    <div class="text-muted small mt-1">
                                        Bebas: <?= $p['masa_tunggu']['tanggal_bisa'] ?>
                                    </div>
                                <?php else: ?>
                                    <span class="text-muted small">Tidak ada batas</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($p['keterangan_admin']): ?>
                                    <span class="text-muted small"><?= $p['keterangan_admin'] ?></span>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td><?= date('d/m/Y H:i', strtotime($p['created_at'])) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

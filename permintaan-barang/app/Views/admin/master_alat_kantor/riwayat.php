<?= $this->extend('layout/admin') ?>
<?= $this->section('content') ?>

<div class="card">
    <div class="card-header bg-info text-white d-flex justify-content-between align-items-center flex-wrap gap-2">
        <span><i class="fas fa-history me-2"></i>Riwayat Stok — <?= esc($judulBarang) ?></span>
        <a href="/admin/master-alat-kantor" class="btn btn-sm btn-light">
            <i class="fas fa-arrow-left me-1"></i>Kembali ke Master
        </a>
    </div>
    <div class="card-body p-0">
        <?php if (empty($riwayat)): ?>
            <div class="text-center text-muted py-5">
                <i class="fas fa-history fa-3x mb-3 opacity-25"></i>
                <p>Belum ada riwayat perubahan stok.</p>
            </div>
        <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Tanggal</th>
                        <th>Nama Barang</th>
                        <th class="text-center">Jenis</th>
                        <th class="text-center">Jumlah</th>
                        <th class="text-center">Stok Sebelum</th>
                        <th class="text-center">Stok Sesudah</th>
                        <th>Keterangan</th>
                        <th>Oleh</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($riwayat as $r): ?>
                    <tr>
                        <td class="text-nowrap"><?= date('d/m/Y H:i', strtotime($r['created_at'])) ?></td>
                        <td><?= esc($r['nama_barang']) ?></td>
                        <td class="text-center">
                            <?php if ($r['jenis'] === 'Masuk'): ?>
                                <span class="badge bg-success"><i class="fas fa-arrow-up me-1"></i>Masuk</span>
                            <?php else: ?>
                                <span class="badge bg-danger"><i class="fas fa-arrow-down me-1"></i>Keluar</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center fw-bold"><?= $r['jumlah'] ?></td>
                        <td class="text-center text-muted"><?= $r['stok_sebelum'] ?></td>
                        <td class="text-center fw-bold <?= (int)$r['stok_sesudah'] === 0 ? 'text-danger' : 'text-success' ?>">
                            <?= $r['stok_sesudah'] ?>
                        </td>
                        <td class="text-muted small"><?= esc($r['keterangan']) ?></td>
                        <td class="text-muted small"><?= esc($r['nama_user'] ?? '-') ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
    <?php if (!empty($riwayat)): ?>
    <div class="card-footer text-muted small">
        Total <?= count($riwayat) ?> transaksi
    </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>

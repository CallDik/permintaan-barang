<?= $this->extend('layout/admin') ?>
<?= $this->section('content') ?>

<!-- Statistik -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card border-start border-primary border-4 h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-primary bg-opacity-15 p-3"><i class="fas fa-boxes text-primary fa-lg"></i></div>
                <div>
                    <div class="text-muted small">Total Jenis Barang</div>
                    <div class="fw-bold fs-4"><?= $stats['total_barang'] ?></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-start border-success border-4 h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-success bg-opacity-15 p-3"><i class="fas fa-layer-group text-success fa-lg"></i></div>
                <div>
                    <div class="text-muted small">Total Stok</div>
                    <div class="fw-bold fs-4"><?= number_format($stats['total_stok']) ?></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-start border-warning border-4 h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-warning bg-opacity-15 p-3"><i class="fas fa-exclamation-triangle text-warning fa-lg"></i></div>
                <div>
                    <div class="text-muted small">Hampir Habis (&lt;10)</div>
                    <div class="fw-bold fs-4 text-warning"><?= $stats['hampir_habis'] ?></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-start border-danger border-4 h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-danger bg-opacity-15 p-3"><i class="fas fa-times-circle text-danger fa-lg"></i></div>
                <div>
                    <div class="text-muted small">Stok Habis</div>
                    <div class="fw-bold fs-4 text-danger"><?= $stats['habis'] ?></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center flex-wrap gap-2">
        <span><i class="fas fa-boxes me-2"></i>Data Stok Alat Kantor</span>
        <div class="d-flex gap-2">
            <a href="/admin/master-alat-kantor/riwayat" class="btn btn-sm btn-light">
                <i class="fas fa-history me-1"></i>Riwayat Stok
            </a>
            <a href="/admin/master-alat-kantor/tambah" class="btn btn-sm btn-warning">
                <i class="fas fa-plus me-1"></i>Tambah Barang
            </a>
        </div>
    </div>

    <?php if (!empty($stats['habis']) && $stats['habis'] > 0): ?>
    <div class="alert alert-warning mb-0 rounded-0 d-flex align-items-center gap-2 border-0">
        <i class="fas fa-info-circle fa-lg"></i>
        <div>
            <strong><?= $stats['habis'] ?> barang</strong> saat ini stoknya <strong>0</strong>.
            Karyawan tidak dapat mengajukan permintaan untuk barang-barang tersebut.
            Klik tombol <span class="badge bg-success"><i class="fas fa-plus-circle"></i> Stok</span> di kolom Aksi untuk menambah stok.
        </div>
    </div>
    <?php endif; ?>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Nama Barang</th>
                        <th class="text-center">Stok</th>
                        <th class="text-center">Status Stok</th>
                        <th class="text-center">Batas Pengajuan Ulang</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($barang)): ?>
                        <tr><td colspan="6" class="text-center text-muted py-4">Belum ada data barang</td></tr>
                    <?php else: ?>
                        <?php $no = 1; foreach ($barang as $b): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td class="fw-semibold"><?= esc($b['nama_barang']) ?></td>
                            <td class="text-center">
                                <span class="fw-bold fs-5 <?= (int)$b['stok'] === 0 ? 'text-danger' : ((int)$b['stok'] < 10 ? 'text-warning' : 'text-success') ?>">
                                    <?= $b['stok'] ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <?php if ((int)$b['stok'] === 0): ?>
                                    <span class="badge bg-danger">Habis</span>
                                <?php elseif ((int)$b['stok'] < 10): ?>
                                    <span class="badge bg-warning text-dark">Hampir Habis</span>
                                <?php else: ?>
                                    <span class="badge bg-success">Tersedia</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <?php $batas = (int)$b['batas_pengajuan_ulang']; ?>
                                <?php if ($batas <= 0): ?>
                                    <span class="badge bg-secondary">Tidak Ada</span>
                                <?php else: ?>
                                    <span class="badge bg-info text-dark">
                                        <i class="fas fa-clock me-1"></i><?= $batas ?> hari
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <a href="/admin/master-alat-kantor/tambah-stok/<?= $b['id'] ?>"
                                   class="btn btn-sm btn-success" title="Tambah Stok">
                                    <i class="fas fa-plus-circle"></i> Stok
                                </a>
                                <a href="/admin/master-alat-kantor/riwayat/<?= $b['id'] ?>"
                                   class="btn btn-sm btn-info text-white" title="Riwayat">
                                    <i class="fas fa-history"></i>
                                </a>
                                <a href="/admin/master-alat-kantor/edit/<?= $b['id'] ?>"
                                   class="btn btn-sm btn-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="/admin/master-alat-kantor/hapus/<?= $b['id'] ?>"
                                   class="btn btn-sm btn-danger" title="Hapus"
                                   onclick="return confirm('Hapus barang \'<?= esc($b['nama_barang']) ?>\'?')">
                                    <i class="fas fa-trash"></i>
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

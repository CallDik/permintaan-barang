<?= $this->extend('layout/admin') ?>
<?= $this->section('content') ?>

<div class="card">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <span><i class="fas fa-warehouse me-2"></i>Manajemen Stok Barang</span>
        <a href="/admin/stok/tambah" class="btn btn-light btn-sm">
            <i class="fas fa-plus me-1"></i>Tambah Barang
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <th>Kategori</th>
                        <th>Stok Tersedia</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($stok)): ?>
                        <tr><td colspan="6" class="text-center text-muted py-4">Belum ada data barang</td></tr>
                    <?php else: ?>
                        <?php $no = 1; foreach ($stok as $s): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><code><?= $s['kode_barang'] ?></code></td>
                            <td><?= $s['nama_barang'] ?></td>
                            <td><span class="badge bg-secondary"><?= $s['kategori'] ?></span></td>
                            <td>
                                <span class="fw-bold <?= $s['stok'] <= 5 ? 'text-danger' : 'text-success' ?>">
                                    <?= $s['stok'] ?>
                                </span>
                                <?php if ($s['stok'] <= 5): ?>
                                    <span class="badge bg-danger ms-1">Hampir Habis</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="/admin/stok/edit/<?= $s['id'] ?>" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="/admin/stok/hapus/<?= $s['id'] ?>" class="btn btn-sm btn-danger"
                                   onclick="return confirm('Hapus barang ini?')">
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

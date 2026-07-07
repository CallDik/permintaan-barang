<?= $this->extend('layout/admin') ?>
<?= $this->section('content') ?>

<div class="card">
    <div class="card-header bg-primary text-white">
        <i class="fas fa-clipboard-list me-2"></i>Data Permintaan
    </div>
    <div class="card-body">
        <!-- Filter & Search -->
        <form method="get" class="row g-2 mb-4">
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="text" name="cari" class="form-control" placeholder="Cari nama karyawan / permintaan..." value="<?= $cari ?>">
                </div>
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="semua" <?= $status === 'semua' ? 'selected' : '' ?>>Semua Status</option>
                    <option value="Pending" <?= $status === 'Pending' ? 'selected' : '' ?>>Pending</option>
                    <option value="Valid" <?= $status === 'Valid' ? 'selected' : '' ?>>Valid</option>
                    <option value="Ditolak" <?= $status === 'Ditolak' ? 'selected' : '' ?>>Ditolak</option>
                </select>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="/admin/permintaan" class="btn btn-secondary">Reset</a>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Kode</th>
                        <th>Karyawan</th>
                        <th>Nama Permintaan</th>
                        <th>Kategori</th>
                        <th>Jumlah</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($permintaan)): ?>
                        <tr><td colspan="9" class="text-center text-muted py-4">Tidak ada data permintaan</td></tr>
                    <?php else: ?>
                        <?php $no = 1; foreach ($permintaan as $p): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><code><?= $p['kode_permintaan'] ?></code></td>
                            <td><?= $p['nama_karyawan'] ?></td>
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
                            <td><?= date('d/m/Y', strtotime($p['created_at'])) ?></td>
                            <td>
                                <a href="/admin/permintaan/detail/<?= $p['id'] ?>" class="btn btn-sm btn-info text-white">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="/admin/permintaan/edit/<?= $p['id'] ?>" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="/admin/permintaan/hapus/<?= $p['id'] ?>" class="btn btn-sm btn-danger"
                                   onclick="return confirm('Hapus permintaan ini?')">
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

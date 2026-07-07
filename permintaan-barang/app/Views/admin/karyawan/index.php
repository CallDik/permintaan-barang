<?= $this->extend('layout/admin') ?>
<?= $this->section('content') ?>

<div class="card">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <span><i class="fas fa-users me-2"></i>Data Karyawan</span>
        <a href="/admin/karyawan/tambah" class="btn btn-light btn-sm">
            <i class="fas fa-plus me-1"></i>Tambah Karyawan
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Username</th>
                        <th>Role</th>
                        <th>Terdaftar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($karyawan)): ?>
                        <tr><td colspan="6" class="text-center text-muted py-4">Belum ada data karyawan</td></tr>
                    <?php else: ?>
                        <?php $no = 1; foreach ($karyawan as $k): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-circle bg-success d-flex align-items-center justify-content-center text-white fw-bold"
                                         style="width:34px;height:34px;font-size:14px;">
                                        <?= strtoupper(substr($k['nama'], 0, 1)) ?>
                                    </div>
                                    <?= $k['nama'] ?>
                                </div>
                            </td>
                            <td><code><?= $k['username'] ?></code></td>
                            <td><span class="badge bg-success">Karyawan</span></td>
                            <td><?= date('d/m/Y', strtotime($k['created_at'])) ?></td>
                            <td>
                                <a href="/admin/karyawan/hapus/<?= $k['id'] ?>" class="btn btn-sm btn-danger"
                                   onclick="return confirm('Hapus karyawan <?= $k['nama'] ?>?')">
                                    <i class="fas fa-trash"></i> Hapus
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

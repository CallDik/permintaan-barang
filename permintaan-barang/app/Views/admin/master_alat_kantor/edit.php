<?= $this->extend('layout/admin') ?>
<?= $this->section('content') ?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
                <span><i class="fas fa-edit me-2"></i>Edit Barang — Data Stok</span>
                <a href="/admin/master-alat-kantor" class="btn btn-sm btn-dark">
                    <i class="fas fa-arrow-left me-1"></i>Kembali
                </a>
            </div>
            <div class="card-body">
                <?php if (session()->getFlashdata('errors')): ?>
                    <div class="alert alert-danger">
                        <?php foreach (session()->getFlashdata('errors') as $err): ?><div><?= $err ?></div><?php endforeach; ?>
                    </div>
                <?php endif; ?>
                <div class="alert alert-info d-flex align-items-center gap-2 mb-3">
                    <i class="fas fa-info-circle"></i>
                    <span>Stok saat ini: <strong><?= $barang['stok'] ?></strong>.
                    Untuk mengubah stok gunakan <a href="/admin/master-alat-kantor/tambah-stok/<?= $barang['id'] ?>">Tambah Stok</a>.</span>
                </div>
                <form action="/admin/master-alat-kantor/update/<?= $barang['id'] ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Barang <span class="text-danger">*</span></label>
                        <input type="text" name="nama_barang" class="form-control"
                               value="<?= old('nama_barang', $barang['nama_barang']) ?>" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">
                            Batas Pengajuan Ulang (hari) <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <input type="number" name="batas_pengajuan_ulang" class="form-control"
                                   min="0" value="<?= old('batas_pengajuan_ulang', $barang['batas_pengajuan_ulang']) ?>" required>
                            <span class="input-group-text">hari</span>
                        </div>
                        <div class="form-text">
                            Isi <strong>0</strong> = tidak ada batas. Perubahan berlaku untuk permintaan berikutnya.
                        </div>
                    </div>
                    <button type="submit" class="btn btn-warning w-100 fw-semibold">
                        <i class="fas fa-save me-2"></i>Simpan Perubahan
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

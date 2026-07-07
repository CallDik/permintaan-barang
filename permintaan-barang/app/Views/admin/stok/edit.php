<?= $this->extend('layout/admin') ?>
<?= $this->section('content') ?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
                <span><i class="fas fa-edit me-2"></i>Edit Barang</span>
                <a href="/admin/stok" class="btn btn-sm btn-dark">
                    <i class="fas fa-arrow-left me-1"></i>Kembali
                </a>
            </div>
            <div class="card-body">
                <form action="/admin/stok/update/<?= $data['id'] ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Kode Barang</label>
                        <input type="text" class="form-control" value="<?= $data['kode_barang'] ?>" disabled>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Barang</label>
                        <input type="text" name="nama_barang" class="form-control" value="<?= $data['nama_barang'] ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Kategori</label>
                        <select name="kategori" class="form-select" required>
                            <option value="Alat Kantor" <?= $data['kategori'] === 'Alat Kantor' ? 'selected' : '' ?>>Alat Kantor</option>
                            <option value="IT" <?= $data['kategori'] === 'IT' ? 'selected' : '' ?>>IT</option>
                            <option value="Fasilitas" <?= $data['kategori'] === 'Fasilitas' ? 'selected' : '' ?>>Fasilitas</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Stok</label>
                        <input type="number" name="stok" class="form-control" value="<?= $data['stok'] ?>" min="0" required>
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

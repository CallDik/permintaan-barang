<?= $this->extend('layout/admin') ?>
<?= $this->section('content') ?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                <span><i class="fas fa-plus me-2"></i>Tambah Barang Stok</span>
                <a href="/admin/stok" class="btn btn-sm btn-light">
                    <i class="fas fa-arrow-left me-1"></i>Kembali
                </a>
            </div>
            <div class="card-body">
                <div class="alert alert-info d-flex align-items-center gap-2">
                    <i class="fas fa-info-circle"></i>
                    <span>Untuk Alat Kantor, gunakan <a href="/admin/master-alat-kantor">Master Alat Kantor</a> agar stok terlacak dengan baik.</span>
                </div>
                <form action="/admin/stok/simpan" method="post">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Barang</label>
                        <input type="text" name="nama_barang" class="form-control" placeholder="Nama barang..." required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Kategori</label>
                        <select name="kategori" class="form-select" required>
                            <option value="">-- Pilih Kategori --</option>
                            <option value="Alat Kantor">Alat Kantor</option>
                            <option value="IT">IT</option>
                            <option value="Fasilitas">Fasilitas</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Stok</label>
                        <input type="number" name="stok" class="form-control" placeholder="Jumlah stok..." min="0" required>
                    </div>
                    <button type="submit" class="btn btn-success w-100">
                        <i class="fas fa-save me-2"></i>Simpan Barang
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

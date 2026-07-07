<?= $this->extend('layout/admin') ?>
<?= $this->section('content') ?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                <span><i class="fas fa-plus-circle me-2"></i>Tambah Stok Barang</span>
                <a href="/admin/master-alat-kantor" class="btn btn-sm btn-light">
                    <i class="fas fa-arrow-left me-1"></i>Kembali
                </a>
            </div>
            <div class="card-body">
                <!-- Info barang -->
                <div class="p-3 bg-light rounded mb-4">
                    <div class="row">
                        <div class="col-6">
                            <div class="text-muted small">Nama Barang</div>
                            <div class="fw-bold"><?= esc($barang['nama_barang']) ?></div>
                        </div>
                        <div class="col-6 text-end">
                            <div class="text-muted small">Stok Saat Ini</div>
                            <div class="fw-bold fs-4 <?= (int)$barang['stok'] === 0 ? 'text-danger' : 'text-success' ?>">
                                <?= $barang['stok'] ?>
                            </div>
                        </div>
                    </div>
                </div>

                <?php if (session()->getFlashdata('errors')): ?>
                    <div class="alert alert-danger">
                        <?php foreach (session()->getFlashdata('errors') as $err): ?>
                            <div><?= $err ?></div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <form action="/admin/master-alat-kantor/simpan-stok/<?= $barang['id'] ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Jumlah Stok Masuk <span class="text-danger">*</span></label>
                        <input type="number" name="jumlah" class="form-control form-control-lg"
                               placeholder="0" min="1" value="<?= old('jumlah') ?>" required
                               id="inputJumlah">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Preview Stok Setelah Penambahan</label>
                        <div class="form-control bg-light fw-bold text-success fs-5" id="previewStok">
                            <?= $barang['stok'] ?>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Keterangan</label>
                        <input type="text" name="keterangan" class="form-control"
                               placeholder="Contoh: Pembelian bulan Juni, dll..."
                               value="<?= old('keterangan') ?>">
                    </div>
                    <button type="submit" class="btn btn-success w-100 fw-semibold">
                        <i class="fas fa-save me-2"></i>Simpan Penambahan Stok
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->section('scripts') ?>
<script>
    const stokLama = <?= (int)$barang['stok'] ?>;
    const inputJumlah = document.getElementById('inputJumlah');
    const previewStok = document.getElementById('previewStok');

    inputJumlah.addEventListener('input', function() {
        const jumlah = parseInt(this.value) || 0;
        const stokBaru = stokLama + jumlah;
        previewStok.textContent = stokBaru;
        previewStok.className = 'form-control bg-light fw-bold fs-5 ' + (stokBaru > 0 ? 'text-success' : 'text-danger');
    });
</script>
<?= $this->endSection() ?>

<?= $this->endSection() ?>

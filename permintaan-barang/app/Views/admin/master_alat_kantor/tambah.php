<?= $this->extend('layout/admin') ?>
<?= $this->section('content') ?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <span><i class="fas fa-plus me-2"></i>Tambah Barang — Data Stok</span>
                <a href="/admin/master-alat-kantor" class="btn btn-sm btn-light">
                    <i class="fas fa-arrow-left me-1"></i>Kembali
                </a>
            </div>
            <div class="card-body">
                <?php if (session()->getFlashdata('errors')): ?>
                    <div class="alert alert-danger">
                        <?php foreach (session()->getFlashdata('errors') as $err): ?><div><?= $err ?></div><?php endforeach; ?>
                    </div>
                <?php endif; ?>
                <form action="/admin/master-alat-kantor/simpan" method="post">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Barang <span class="text-danger">*</span></label>
                        <input type="text" name="nama_barang" class="form-control"
                               placeholder="Contoh: Kertas A4, Pena, dll..."
                               value="<?= old('nama_barang') ?>" required>
                        <div class="form-text">Nama barang harus unik</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Stok Awal <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number" name="stok_awal" class="form-control"
                                   placeholder="0" min="0" value="<?= old('stok_awal', 0) ?>" required id="inputStokAwal">
                            <span class="input-group-text">unit</span>
                        </div>
                        <div class="form-text">
                            Jumlah stok awal saat barang ini pertama kali didaftarkan.<br>
                            Isi <strong>0</strong> jika belum ada stok (bisa ditambahkan nanti lewat tombol <strong>+ Stok</strong> di halaman Data Stok).
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Keterangan Stok Awal</label>
                        <input type="text" name="keterangan_stok_awal" class="form-control"
                               placeholder="Contoh: Stok pembuka, Pembelian awal bulan, dll..."
                               value="<?= old('keterangan_stok_awal') ?>">
                        <div class="form-text">Hanya diisi jika stok awal &gt; 0. Akan dicatat di riwayat stok.</div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">
                            Batas Pengajuan Ulang (hari)
                            <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <input type="number" name="batas_pengajuan_ulang" class="form-control"
                                   placeholder="0" min="0" value="<?= old('batas_pengajuan_ulang', 0) ?>" required>
                            <span class="input-group-text">hari</span>
                        </div>
                        <div class="form-text">
                            Isi <strong>0</strong> jika tidak ada batas (karyawan boleh ajukan kapan saja).<br>
                            Contoh: isi <strong>7</strong> artinya karyawan harus menunggu 7 hari setelah permintaan disetujui sebelum bisa mengajukan barang yang sama lagi.
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-save me-2"></i>Simpan Barang
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

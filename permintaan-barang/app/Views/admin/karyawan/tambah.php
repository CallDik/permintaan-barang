<?= $this->extend('layout/admin') ?>
<?= $this->section('content') ?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                <span><i class="fas fa-user-plus me-2"></i>Tambah Karyawan</span>
                <a href="/admin/karyawan" class="btn btn-sm btn-light">
                    <i class="fas fa-arrow-left me-1"></i>Kembali
                </a>
            </div>
            <div class="card-body">
                <?php if (session()->getFlashdata('errors')): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            <?php foreach (session()->getFlashdata('errors') as $err): ?>
                                <li><?= $err ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form action="/admin/karyawan/simpan" method="post">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control"
                            placeholder="Masukkan nama lengkap..."
                            value="<?= old('nama') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Username <span class="text-danger">*</span></label>
                        <input type="text" name="username" class="form-control"
                            placeholder="Masukkan username..."
                            value="<?= old('username') ?>" required>
                        <div class="form-text">Username harus unik, tidak boleh sama dengan yang sudah ada.</div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Password <span class="text-danger">*</span></label>
                        <input type="password" name="password" class="form-control"
                            placeholder="Minimal 6 karakter..." required>
                    </div>
                    <button type="submit" class="btn btn-success w-100 fw-semibold">
                        <i class="fas fa-user-plus me-2"></i>Tambah Karyawan
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

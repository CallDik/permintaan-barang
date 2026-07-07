<?= $this->extend('layout/admin') ?>
<?= $this->section('content') ?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
                <span><i class="fas fa-edit me-2"></i>Edit Permintaan</span>
                <a href="/admin/permintaan" class="btn btn-sm btn-dark">
                    <i class="fas fa-arrow-left me-1"></i>Kembali
                </a>
            </div>
            <div class="card-body">
                <form action="/admin/permintaan/update/<?= $data['id'] ?>" method="post" id="formEditPermintaan">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Kode Permintaan</label>
                        <input type="text" class="form-control" value="<?= $data['kode_permintaan'] ?>" disabled>
                    </div>

                    <!-- Kategori -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Kategori</label>
                        <select name="kategori" id="selectKategori" class="form-select" required>
                            <option value="IT"           <?= $data['kategori'] === 'IT'           ? 'selected' : '' ?>>IT</option>
                            <option value="Fasilitas"    <?= $data['kategori'] === 'Fasilitas'    ? 'selected' : '' ?>>Fasilitas</option>
                            <option value="Alat Kantor"  <?= $data['kategori'] === 'Alat Kantor'  ? 'selected' : '' ?>>Alat Kantor</option>
                        </select>
                    </div>

                    <!-- Nama untuk non-Alat Kantor -->
                    <div class="mb-3" id="fieldNamaBiasa">
                        <label class="form-label fw-semibold">Nama Permintaan</label>
                        <input type="text" name="nama_permintaan" id="inputNama"
                               class="form-control" value="<?= $data['nama_permintaan'] ?>">
                    </div>

                    <!-- Dropdown Alat Kantor -->
                    <div class="mb-3" id="fieldAlatKantor" style="display:none;">
                        <label class="form-label fw-semibold">Pilih Barang Alat Kantor</label>
                        <select name="barang_id" id="selectBarang" class="form-select">
                            <option value="">-- Pilih Barang --</option>
                            <?php foreach ($master_barang as $mb): ?>
                                <option value="<?= $mb['id'] ?>"
                                        data-stok="<?= $mb['stok'] ?>"
                                        <?= (int)$data['barang_id'] === (int)$mb['id'] ? 'selected' : '' ?>>
                                    <?= esc($mb['nama_barang']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div id="infoStokEdit" class="mt-2 text-muted small"></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Jumlah</label>
                        <input type="number" name="jumlah" class="form-control" value="<?= $data['jumlah'] ?>" min="1" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="3"><?= $data['deskripsi'] ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Status</label>
                        <select name="status" class="form-select">
                            <option value="Pending" <?= $data['status'] === 'Pending' ? 'selected' : '' ?>>Pending</option>
                            <option value="Valid"   <?= $data['status'] === 'Valid'   ? 'selected' : '' ?>>Valid (Disetujui)</option>
                            <option value="Ditolak" <?= $data['status'] === 'Ditolak' ? 'selected' : '' ?>>Ditolak</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Keterangan Admin</label>
                        <textarea name="keterangan_admin" class="form-control" rows="2"><?= $data['keterangan_admin'] ?></textarea>
                    </div>
                    <button type="submit" class="btn btn-warning fw-semibold">
                        <i class="fas fa-save me-2"></i>Simpan Perubahan
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->section('scripts') ?>
<script>
const selectKategori  = document.getElementById('selectKategori');
const fieldNamaBiasa  = document.getElementById('fieldNamaBiasa');
const fieldAlatKantor = document.getElementById('fieldAlatKantor');
const selectBarang    = document.getElementById('selectBarang');
const inputNama       = document.getElementById('inputNama');
const infoStokEdit    = document.getElementById('infoStokEdit');

function toggleKategoriEdit() {
    const kat = selectKategori.value;
    if (kat === 'Alat Kantor') {
        fieldNamaBiasa.style.display  = 'none';
        fieldAlatKantor.style.display = 'block';
        inputNama.required = false;
    } else {
        fieldNamaBiasa.style.display  = 'block';
        fieldAlatKantor.style.display = 'none';
        inputNama.required = true;
    }
    updateStokInfo();
}

function updateStokInfo() {
    const opt = selectBarang.options[selectBarang.selectedIndex];
    if (!opt || !opt.value) { infoStokEdit.textContent = ''; return; }
    const stok = parseInt(opt.dataset.stok) || 0;
    infoStokEdit.innerHTML = 'Stok tersedia: <strong>' + stok + '</strong>';
    infoStokEdit.className = 'mt-2 small fw-semibold ' + (stok === 0 ? 'text-danger' : stok < 10 ? 'text-warning' : 'text-success');
}

selectKategori.addEventListener('change', toggleKategoriEdit);
selectBarang.addEventListener('change', updateStokInfo);

// Init
toggleKategoriEdit();
</script>
<?= $this->endSection() ?>

<?= $this->endSection() ?>

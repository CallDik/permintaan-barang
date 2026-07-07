<?= $this->extend('layout/karyawan') ?>
<?= $this->section('content') ?>

<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card">
            <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                <span><i class="fas fa-plus me-2"></i>Tambah Permintaan</span>
                <a href="/karyawan/dashboard" class="btn btn-sm btn-light">
                    <i class="fas fa-arrow-left me-1"></i>Kembali
                </a>
            </div>
            <div class="card-body">

                <?php if (session()->getFlashdata('errors')): ?>
                    <div class="alert alert-danger">
                        <?php foreach (session()->getFlashdata('errors') as $err): ?><div><?= $err ?></div><?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger d-flex align-items-center gap-2">
                        <i class="fas fa-exclamation-circle fa-lg"></i>
                        <span><?= session()->getFlashdata('error') ?></span>
                    </div>
                <?php endif; ?>

                <?php $errInterval = session()->getFlashdata('error_interval'); if ($errInterval): ?>
                <div class="alert alert-warning border border-warning">
                    <div class="fw-bold mb-2"><i class="fas fa-clock me-2"></i>Permintaan Tidak Dapat Diajukan</div>
                    <p class="mb-1">
                        Kamu sudah pernah mengajukan <strong><?= esc($errInterval['nama_barang']) ?></strong>
                        <?php if (!empty($errInterval['kode_terakhir'])): ?>
                            (<code><?= esc($errInterval['kode_terakhir']) ?></code>)
                        <?php endif; ?>
                        pada <strong><?= esc($errInterval['tanggal_ajukan'] ?? '-') ?></strong>.
                        Batas pengajuan ulang adalah <strong><?= $errInterval['batas'] ?> hari</strong> sejak saat pengajuan.
                    </p>
                    <hr class="my-2">
                    <div class="d-flex gap-4">
                        <div>
                            <div class="text-muted small">Sisa Waktu Tunggu</div>
                            <div class="fw-bold fs-5 text-warning"><?= $errInterval['sisa_hari'] ?> hari lagi</div>
                        </div>
                        <div>
                            <div class="text-muted small">Bisa Diajukan Kembali</div>
                            <div class="fw-bold fs-5 text-success"><?= $errInterval['tanggal_bisa'] ?></div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <form action="/karyawan/permintaan/simpan" method="post" id="formPermintaan">
                    <?= csrf_field() ?>

                    <!-- Kategori -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Kategori <span class="text-danger">*</span></label>
                        <select name="kategori" id="selectKategori" class="form-select" required>
                            <option value="">-- Pilih Kategori --</option>
                            <option value="IT"          <?= old('kategori') === 'IT'          ? 'selected' : '' ?>>IT</option>
                            <option value="Fasilitas"   <?= old('kategori') === 'Fasilitas'   ? 'selected' : '' ?>>Fasilitas</option>
                            <option value="Alat Kantor" <?= old('kategori') === 'Alat Kantor' ? 'selected' : '' ?>>Alat Kantor</option>
                        </select>
                    </div>

                    <!-- Nama untuk non-Alat Kantor -->
                    <div class="mb-3" id="fieldNamaBiasa">
                        <label class="form-label fw-semibold">Nama Permintaan <span class="text-danger">*</span></label>
                        <input type="text" name="nama_permintaan" id="inputNamaPermintaan"
                               class="form-control" placeholder="Contoh: Mouse, Monitor, dll..."
                               value="<?= old('nama_permintaan') ?>">
                    </div>

                    <!-- Dropdown Alat Kantor -->
                    <div class="mb-3" id="fieldAlatKantor" style="display:none;">
                        <label class="form-label fw-semibold">Pilih Barang <span class="text-danger">*</span></label>
                        <select name="barang_id" id="selectBarang" class="form-select">
                            <option value="">-- Pilih Barang --</option>
                            <?php foreach ($master_barang as $mb): ?>
                                <option value="<?= $mb['id'] ?>"
                                    <?= (int)old('barang_id') === (int)$mb['id'] ? 'selected' : '' ?>>
                                    <?= esc($mb['nama_barang']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>

                        <!-- Loading spinner saat AJAX -->
                        <div id="loadingInfo" class="mt-2 text-muted small" style="display:none;">
                            <i class="fas fa-spinner fa-spin me-1"></i>Memuat info stok...
                        </div>

                        <!-- Info stok & batas (diisi via AJAX) -->
                        <div id="infoBarangPanel" class="mt-2" style="display:none;">
                            <div class="p-3 rounded border bg-light">
                                <div class="row g-2">
                                    <div class="col-6">
                                        <div class="text-muted small">Stok Tersedia</div>
                                        <div class="fw-bold fs-5" id="infoStokJumlah">—</div>
                                    </div>
                                    <div class="col-6">
                                        <div class="text-muted small">Batas Pengajuan Ulang</div>
                                        <div class="fw-bold" id="infoBatas">—</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Alert stok habis -->
                        <div id="alertStokHabis" class="alert alert-danger mt-2 mb-0" style="display:none;">
                            <div class="fw-semibold mb-1">
                                <i class="fas fa-times-circle me-1"></i>Stok barang ini <strong>habis</strong>.
                            </div>
                            <div class="small">Permintaan tidak dapat diajukan. Silakan hubungi Admin untuk menambah stok, atau pilih barang lain yang masih tersedia.</div>
                        </div>

                        <!-- Alert interval masa tunggu -->
                        <div id="alertInterval" class="alert alert-warning mt-2 mb-0" style="display:none;">
                            <div class="fw-semibold mb-1"><i class="fas fa-clock me-1"></i>Masa Tunggu Belum Selesai</div>
                            <div id="alertIntervalDetail"></div>
                        </div>
                    </div>

                    <!-- Jumlah -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Jumlah <span class="text-danger">*</span></label>
                        <input type="number" name="jumlah" id="inputJumlah"
                               class="form-control" placeholder="Masukkan jumlah..." min="1"
                               value="<?= old('jumlah') ?>" required>
                        <div id="pesanStok" class="form-text text-danger fw-semibold" style="display:none;"></div>
                        <div class="form-text" id="infoJumlah">Jumlah permintaan harus lebih dari 0</div>
                    </div>

                    <!-- Deskripsi -->
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Deskripsi <span class="text-danger">*</span></label>
                        <textarea name="deskripsi" class="form-control" rows="4"
                            placeholder="Jelaskan kebutuhan permintaan ini..." required><?= old('deskripsi') ?></textarea>
                    </div>

                    <button type="submit" class="btn btn-success w-100 fw-semibold" id="btnSubmit">
                        <i class="fas fa-paper-plane me-2"></i>Kirim Permintaan
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->section('scripts') ?>
<script>
const selectKategori   = document.getElementById('selectKategori');
const fieldNamaBiasa   = document.getElementById('fieldNamaBiasa');
const fieldAlatKantor  = document.getElementById('fieldAlatKantor');
const inputNama        = document.getElementById('inputNamaPermintaan');
const selectBarang     = document.getElementById('selectBarang');
const inputJumlah      = document.getElementById('inputJumlah');
const loadingInfo      = document.getElementById('loadingInfo');
const infoBarangPanel  = document.getElementById('infoBarangPanel');
const infoStokJumlah   = document.getElementById('infoStokJumlah');
const infoBatas        = document.getElementById('infoBatas');
const alertStokHabis   = document.getElementById('alertStokHabis');
const alertInterval    = document.getElementById('alertInterval');
const alertIntervalDet = document.getElementById('alertIntervalDetail');
const pesanStok        = document.getElementById('pesanStok');
const infoJumlah       = document.getElementById('infoJumlah');
const btnSubmit        = document.getElementById('btnSubmit');

// Stok dari server (diisi saat AJAX selesai)
let stokTersedia = 0;
let bolehAjukan  = true;

// ---- Sembunyikan semua info panel ----
function resetInfoPanel() {
    loadingInfo.style.display     = 'none';
    infoBarangPanel.style.display = 'none';
    alertStokHabis.style.display  = 'none';
    alertInterval.style.display   = 'none';
    pesanStok.style.display       = 'none';
    stokTersedia = 0;
    bolehAjukan  = true;
}

// ---- Toggle field berdasar kategori ----
function toggleKategori() {
    const kat = selectKategori.value;
    if (kat === 'Alat Kantor') {
        fieldNamaBiasa.style.display  = 'none';
        fieldAlatKantor.style.display = 'block';
        inputNama.required    = false;
        selectBarang.required = true;
        // Langsung fetch kalau ada barang yang sudah terpilih
        if (selectBarang.value) {
            fetchInfoBarang(selectBarang.value);
        } else {
            resetInfoPanel();
            btnSubmit.disabled = false;
        }
    } else {
        fieldNamaBiasa.style.display  = 'block';
        fieldAlatKantor.style.display = 'none';
        inputNama.required    = true;
        selectBarang.required = false;
        resetInfoPanel();
        btnSubmit.disabled = false;
    }
}

// ---- Fetch AJAX stok + interval per user ----
function fetchInfoBarang(barangId) {
    resetInfoPanel();
    btnSubmit.disabled = true;
    loadingInfo.style.display = 'block';

    // Gunakan URL absolut dari base_url untuk menghindari masalah path
    var url = '<?= base_url('karyawan/api/stok-barang') ?>/' + barangId;

    fetch(url, {
        method: 'GET',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        credentials: 'same-origin' // kirim session cookie
    })
        .then(function(r) {
            if (!r.ok) throw new Error('HTTP ' + r.status);
            return r.json();
        })
        .then(function(data) {
            loadingInfo.style.display = 'none';

            if (data.error) {
                // Tampilkan error di panel supaya kelihatan
                infoStokJumlah.textContent = '?';
                infoStokJumlah.className   = 'fw-bold fs-5 text-danger';
                infoBatas.textContent      = '?';
                infoBarangPanel.style.display = 'block';
                btnSubmit.disabled = false;
                return;
            }

            // Simpan data dari server
            stokTersedia = parseInt(data.stok) || 0;
            bolehAjukan  = data.boleh_ajukan;

            // ---- SELALU tampilkan info stok & batas, apapun kondisinya ----
            infoStokJumlah.textContent = stokTersedia;
            infoStokJumlah.className = 'fw-bold fs-5 ' +
                (stokTersedia === 0 ? 'text-danger' : stokTersedia < 10 ? 'text-warning' : 'text-success');

            const batas = parseInt(data.batas_pengajuan_ulang) || 0;
            infoBatas.textContent = batas > 0 ? batas + ' hari' : 'Tidak Ada';
            infoBatas.className   = 'fw-bold ' + (batas > 0 ? 'text-info' : 'text-muted');
            infoBarangPanel.style.display = 'block'; // selalu tampil

            // ---- Cek stok habis (prioritas tertinggi) ----
            if (stokTersedia === 0) {
                alertStokHabis.style.display = 'block';
                btnSubmit.disabled = true;
                return;
            }

            // ---- Cek interval masa tunggu ----
            // Stok tetap terlihat di atas, hanya submit yang diblokir
            if (!bolehAjukan) {
                var html = '<div class="mb-2">';
                html += '<i class="fas fa-clock me-1"></i>Kamu sudah pernah mengajukan barang ini';
                if (data.kode_terakhir) {
                    html += ' (<code>' + data.kode_terakhir + '</code>';
                    if (data.status_terakhir) {
                        var badgeColor = data.status_terakhir === 'Valid' ? 'success' : 'warning';
                        html += ' <span class="badge bg-' + badgeColor + '">' + data.status_terakhir + '</span>';
                    }
                    html += ')';
                }
                html += ' pada <strong>' + (data.tanggal_ajukan || '-') + '</strong>.';
                html += '</div>';
                html += '<div class="row g-2 mt-1">';
                html += '  <div class="col-6"><div class="text-muted small">Sisa Waktu Tunggu</div>';
                html += '    <div class="fw-bold fs-5 text-warning">' + data.sisa_hari + ' hari lagi</div></div>';
                html += '  <div class="col-6"><div class="text-muted small">Bisa Diajukan Kembali</div>';
                html += '    <div class="fw-bold fs-5 text-success">' + data.tanggal_bisa + '</div></div>';
                html += '</div>';
                alertIntervalDet.innerHTML = html;
                alertInterval.style.display = 'block';
                btnSubmit.disabled = true;
                return; // blokir submit, tapi info stok di atas tetap tampil
            }

            // ---- Semua aman, bisa ajukan ----
            btnSubmit.disabled = false;
            validasiJumlah();
        })
        .catch(function(err) {
            loadingInfo.style.display = 'none';
            infoStokJumlah.textContent = 'Error';
            infoStokJumlah.className   = 'fw-bold fs-5 text-danger';
            infoBatas.textContent      = '-';
            infoBarangPanel.style.display = 'block';
            btnSubmit.disabled = false;
        });
}

// ---- Validasi jumlah vs stok ----
function validasiJumlah() {
    if (selectKategori.value !== 'Alat Kantor') return;
    if (!bolehAjukan || stokTersedia === 0) return;

    const jumlah = parseInt(inputJumlah.value) || 0;
    if (jumlah > stokTersedia) {
        pesanStok.textContent    = 'Jumlah permintaan melebihi stok yang tersedia (' + stokTersedia + ').';
        pesanStok.style.display  = 'block';
        infoJumlah.style.display = 'none';
        btnSubmit.disabled = true;
    } else {
        pesanStok.style.display  = 'none';
        infoJumlah.style.display = 'block';
        btnSubmit.disabled = false;
    }
}

// ---- Event listeners ----
selectKategori.addEventListener('change', toggleKategori);
selectBarang.addEventListener('change', function() {
    if (this.value) {
        fetchInfoBarang(this.value);
    } else {
        resetInfoPanel();
        btnSubmit.disabled = false;
    }
});
inputJumlah.addEventListener('input', validasiJumlah);

// ---- Init saat halaman load ----
toggleKategori();
</script>
<?= $this->endSection() ?>

<?= $this->endSection() ?>

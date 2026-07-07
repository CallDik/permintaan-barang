<?= $this->extend('layout/admin') ?>
<?= $this->section('content') ?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <span><i class="fas fa-info-circle me-2"></i>Detail Permintaan</span>
                <a href="/admin/permintaan" class="btn btn-sm btn-light">
                    <i class="fas fa-arrow-left me-1"></i>Kembali
                </a>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr><th width="35%">Kode Permintaan</th><td><code><?= $data['kode_permintaan'] ?></code></td></tr>
                    <tr><th>Nama Karyawan</th><td><?= $data['nama_karyawan'] ?></td></tr>
                    <tr><th>Nama Permintaan</th><td><?= $data['nama_permintaan'] ?></td></tr>
                    <tr><th>Kategori</th><td><span class="badge bg-secondary"><?= $data['kategori'] ?></span></td></tr>
                    <tr><th>Jumlah</th><td><?= $data['jumlah'] ?></td></tr>
                    <tr><th>Deskripsi</th><td><?= $data['deskripsi'] ?></td></tr>
                    <tr><th>Tanggal</th><td><?= date('d/m/Y H:i', strtotime($data['created_at'])) ?></td></tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            <?php if ($data['status'] === 'Pending'): ?>
                                <span class="badge bg-warning text-dark fs-6">Pending</span>
                            <?php elseif ($data['status'] === 'Valid'): ?>
                                <span class="badge bg-success fs-6">Valid</span>
                            <?php else: ?>
                                <span class="badge bg-danger fs-6">Ditolak</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php if ($data['keterangan_admin']): ?>
                    <tr><th>Keterangan Admin</th><td><?= $data['keterangan_admin'] ?></td></tr>
                    <?php endif; ?>
                </table>

                <?php if ($data['status'] === 'Pending'): ?>
                <hr>
                <div class="row g-3">
                    <div class="col-md-6">
                        <form action="/admin/permintaan/setujui/<?= $data['id'] ?>" method="post">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn btn-success w-100"
                                onclick="return confirm('Setujui permintaan ini?')">
                                <i class="fas fa-check me-2"></i>Setujui Permintaan
                            </button>
                        </form>
                    </div>
                    <div class="col-md-6">
                        <button type="button" class="btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="#modalTolak">
                            <i class="fas fa-times me-2"></i>Tolak Permintaan
                        </button>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tolak -->
<div class="modal fade" id="modalTolak" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="fas fa-times me-2"></i>Tolak Permintaan</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="/admin/permintaan/tolak/<?= $data['id'] ?>" method="post">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <label class="form-label fw-semibold">Alasan Penolakan <span class="text-danger">*</span></label>
                    <textarea name="keterangan_admin" class="form-control" rows="4"
                        placeholder="Tulis alasan penolakan..." required></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Tolak Permintaan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

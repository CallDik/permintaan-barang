<?php namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PermintaanModel;
use App\Models\MasterAlatKantorModel;

class Permintaan extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $model  = new PermintaanModel();
        $status = $this->request->getGet('status') ?? 'semua';
        $cari   = $this->request->getGet('cari') ?? '';

        $builder = $this->db->table('permintaan p')
            ->select('p.*, u.nama as nama_karyawan')
            ->join('users u', 'u.id = p.user_id');

        if ($status && $status !== 'semua') {
            $builder->where('p.status', $status);
        }

        if ($cari) {
            $builder->groupStart()
                ->like('u.nama', $cari)
                ->orLike('p.nama_permintaan', $cari)
                ->groupEnd();
        }

        $permintaan = $builder->orderBy('p.created_at', 'DESC')->get()->getResultArray();

        return view('admin/permintaan/index', [
            'title'      => 'Data Permintaan',
            'permintaan' => $permintaan,
            'status'     => $status,
            'cari'       => $cari,
            'pending'    => $model->countPending(),
        ]);
    }

    public function detail($id)
    {
        $model = new PermintaanModel();
        $data  = $model->getDetail($id);

        if (!$data) {
            return redirect()->to('/admin/permintaan')->with('error', 'Data tidak ditemukan!');
        }

        // Ambil info stok barang jika Alat Kantor
        $stokBarang = null;
        if ($data['kategori'] === 'Alat Kantor' && !empty($data['barang_id'])) {
            $masterModel = new MasterAlatKantorModel();
            $stokBarang  = $masterModel->find($data['barang_id']);
        }

        return view('admin/permintaan/detail', [
            'title'      => 'Detail Permintaan',
            'data'       => $data,
            'stokBarang' => $stokBarang,
            'pending'    => $model->countPending(),
        ]);
    }

    public function setujui($id)
    {
        $model       = new PermintaanModel();
        $masterModel = new MasterAlatKantorModel();
        $permintaan  = $model->find($id);

        if (!$permintaan) {
            return redirect()->to('/admin/permintaan')->with('error', 'Data tidak ditemukan!');
        }

        // Pastikan belum pernah disetujui (cegah pengurangan ganda)
        if ($permintaan['status'] === 'Valid') {
            return redirect()->back()->with('error', 'Permintaan ini sudah disetujui sebelumnya!');
        }

        // ---- Validasi & kurangi stok Alat Kantor ----
        if ($permintaan['kategori'] === 'Alat Kantor') {
            // Cari barang via barang_id (lebih akurat) atau fallback nama
            $barang = null;
            if (!empty($permintaan['barang_id'])) {
                $barang = $masterModel->find($permintaan['barang_id']);
            }
            if (!$barang) {
                $barang = $masterModel->getByNama($permintaan['nama_permintaan']);
            }

            if (!$barang) {
                return redirect()->back()->with('error', 'Barang tidak ditemukan di master alat kantor!');
            }

            if ($permintaan['jumlah'] > (int) $barang['stok']) {
                return redirect()->back()->with('error',
                    'Stok tidak mencukupi! Stok tersedia ' . $barang['nama_barang'] . ': ' . $barang['stok']);
            }

            // Kurangi stok — satu kali saja (flag stok_sudah_dikurangi)
            $userId   = session()->get('id_user');
            $namaUser = session()->get('nama');
            $keterangan = 'Permintaan #' . $permintaan['kode_permintaan'] . ' disetujui';

            $masterModel->kurangiStok($barang['id'], $permintaan['jumlah'], $userId, $namaUser, $keterangan);

            // Tandai sudah dikurangi
            $model->update($id, ['stok_sudah_dikurangi' => 1]);
        }

        $model->update($id, [
            'status'            => 'Valid',
            'keterangan_admin'  => 'Permintaan disetujui.',
            'tanggal_disetujui' => date('Y-m-d H:i:s'),
        ]);
        return redirect()->to('/admin/permintaan')->with('success', 'Permintaan berhasil disetujui!');
    }

    public function tolak($id)
    {
        $model      = new PermintaanModel();
        $keterangan = $this->request->getPost('keterangan_admin');

        if (empty($keterangan)) {
            return redirect()->back()->with('error', 'Keterangan penolakan wajib diisi!');
        }

        // Jika sebelumnya sudah disetujui dan stok sudah dikurangi, kembalikan stok
        $permintaan = $model->find($id);
        if ($permintaan && $permintaan['status'] === 'Valid' && $permintaan['stok_sudah_dikurangi'] == 1
            && $permintaan['kategori'] === 'Alat Kantor') {
            $masterModel = new MasterAlatKantorModel();
            $barang = null;
            if (!empty($permintaan['barang_id'])) {
                $barang = $masterModel->find($permintaan['barang_id']);
            }
            if (!$barang) {
                $barang = $masterModel->getByNama($permintaan['nama_permintaan']);
            }
            if ($barang) {
                $userId   = session()->get('id_user');
                $namaUser = session()->get('nama');
                $masterModel->tambahStok($barang['id'], $permintaan['jumlah'], $userId, $namaUser,
                    'Permintaan #' . $permintaan['kode_permintaan'] . ' ditolak, stok dikembalikan');
            }
            $model->update($id, ['stok_sudah_dikurangi' => 0]);
        }

        $model->update($id, ['status' => 'Ditolak', 'keterangan_admin' => $keterangan]);
        return redirect()->to('/admin/permintaan')->with('success', 'Permintaan berhasil ditolak!');
    }

    public function edit($id)
    {
        $model       = new PermintaanModel();
        $masterModel = new MasterAlatKantorModel();
        $data        = $model->getDetail($id);

        return view('admin/permintaan/edit', [
            'title'         => 'Edit Permintaan',
            'data'          => $data,
            'master_barang' => $masterModel->getAllOrderedByName(),
            'pending'       => $model->countPending(),
        ]);
    }

    public function update($id)
    {
        $model      = new PermintaanModel();
        $permintaan = $model->find($id);

        // Jika status berubah ke Valid dan belum pernah dikurangi → kurangi stok
        $statusBaru = $this->request->getPost('status');
        $masterModel = new MasterAlatKantorModel();

        if ($statusBaru === 'Valid'
            && $permintaan['status'] !== 'Valid'
            && $permintaan['stok_sudah_dikurangi'] == 0
            && $permintaan['kategori'] === 'Alat Kantor') {

            $barang = null;
            if (!empty($permintaan['barang_id'])) {
                $barang = $masterModel->find($permintaan['barang_id']);
            }
            if (!$barang) {
                $barang = $masterModel->getByNama($permintaan['nama_permintaan']);
            }

            if ($barang && $permintaan['jumlah'] <= (int) $barang['stok']) {
                $userId   = session()->get('id_user');
                $namaUser = session()->get('nama');
                $masterModel->kurangiStok($barang['id'], $permintaan['jumlah'], $userId, $namaUser,
                    'Permintaan #' . $permintaan['kode_permintaan'] . ' disetujui via edit');
                $model->update($id, ['stok_sudah_dikurangi' => 1]);
            }
        }

        // Jika status berubah dari Valid ke lain dan stok sudah dikurangi → kembalikan stok
        if ($statusBaru !== 'Valid'
            && $permintaan['status'] === 'Valid'
            && $permintaan['stok_sudah_dikurangi'] == 1
            && $permintaan['kategori'] === 'Alat Kantor') {

            $barang = null;
            if (!empty($permintaan['barang_id'])) {
                $barang = $masterModel->find($permintaan['barang_id']);
            }
            if (!$barang) {
                $barang = $masterModel->getByNama($permintaan['nama_permintaan']);
            }
            if ($barang) {
                $userId   = session()->get('id_user');
                $namaUser = session()->get('nama');
                $masterModel->tambahStok($barang['id'], $permintaan['jumlah'], $userId, $namaUser,
                    'Permintaan #' . $permintaan['kode_permintaan'] . ' status diubah dari Valid, stok dikembalikan');
            }
            $model->update($id, ['stok_sudah_dikurangi' => 0]);
        }

        $model->update($id, [
            'nama_permintaan'  => $this->request->getPost('nama_permintaan'),
            'kategori'         => $this->request->getPost('kategori'),
            'jumlah'           => $this->request->getPost('jumlah'),
            'deskripsi'        => $this->request->getPost('deskripsi'),
            'status'           => $statusBaru,
            'keterangan_admin' => $this->request->getPost('keterangan_admin'),
        ]);

        return redirect()->to('/admin/permintaan')->with('success', 'Data berhasil diupdate!');
    }

    public function hapus($id)
    {
        $model = new PermintaanModel();
        $model->delete($id);
        return redirect()->to('/admin/permintaan')->with('success', 'Data berhasil dihapus!');
    }
}

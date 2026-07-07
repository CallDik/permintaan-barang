<?php namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\MasterAlatKantorModel;
use App\Models\PermintaanModel;

class MasterAlatKantor extends BaseController
{
    protected MasterAlatKantorModel $model;
    protected PermintaanModel $permintaanModel;

    public function __construct()
    {
        $this->model           = new MasterAlatKantorModel();
        $this->permintaanModel = new PermintaanModel();
    }

    public function index()
    {
        return view('admin/master_alat_kantor/index', [
            'title'   => 'Data Stok',
            'barang'  => $this->model->getAllOrderedByName(),
            'stats'   => $this->model->getDashboardStats(),
            'pending' => $this->permintaanModel->countPending(),
        ]);
    }

    public function tambah()
    {
        return view('admin/master_alat_kantor/tambah', [
            'title'   => 'Tambah Barang — Data Stok',
            'pending' => $this->permintaanModel->countPending(),
        ]);
    }

    public function simpan()
    {
        $rules = [
            'nama_barang'           => 'required|min_length[2]|max_length[150]',
            'stok_awal'             => 'required|integer|greater_than_equal_to[0]',
            'batas_pengajuan_ulang' => 'required|integer|greater_than_equal_to[0]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $nama = trim($this->request->getPost('nama_barang'));
        if ($this->model->getByNama($nama)) {
            return redirect()->back()->withInput()->with('error', 'Barang "' . $nama . '" sudah ada di Data Stok!');
        }

        $stokAwal    = (int) $this->request->getPost('stok_awal');
        $batasUlang  = (int) $this->request->getPost('batas_pengajuan_ulang');
        $keterangan  = trim($this->request->getPost('keterangan_stok_awal') ?: '');

        // Insert barang baru dengan stok awal (bukan di-hardcode ke 0 lagi)
        $this->model->insert([
            'nama_barang'           => $nama,
            'stok'                  => $stokAwal,
            'batas_pengajuan_ulang' => $batasUlang,
        ]);

        // Catat di riwayat stok jika stok awal > 0
        if ($stokAwal > 0) {
            $insertId = $this->model->getInsertID();
            $userId   = session()->get('id_user');
            $namaUser = session()->get('nama') ?: 'Admin';
            $ket      = $keterangan !== '' ? $keterangan : 'Stok awal saat barang didaftarkan';

            $db = \Config\Database::connect();
            $db->table('riwayat_stok')->insert([
                'barang_id'    => $insertId,
                'nama_barang'  => $nama,
                'jenis'        => 'Masuk',
                'jumlah'       => $stokAwal,
                'stok_sebelum' => 0,
                'stok_sesudah' => $stokAwal,
                'keterangan'   => $ket,
                'user_id'      => $userId,
                'nama_user'    => $namaUser,
                'created_at'   => date('Y-m-d H:i:s'),
            ]);
        }

        $pesan = 'Barang "' . $nama . '" berhasil ditambahkan!';
        if ($stokAwal > 0) {
            $pesan .= ' Stok awal: ' . $stokAwal . ' unit.';
        } else {
            $pesan .= ' Stok masih 0 — silakan klik tombol <strong>+ Stok</strong> untuk menambah quantity agar bisa diajukan karyawan.';
        }

        return redirect()->to('/admin/master-alat-kantor')->with('success', $pesan);
    }

    public function edit($id)
    {
        $barang = $this->model->find($id);
        if (!$barang) {
            return redirect()->to('/admin/master-alat-kantor')->with('error', 'Barang tidak ditemukan!');
        }

        return view('admin/master_alat_kantor/edit', [
            'title'   => 'Edit Barang — Data Stok',
            'barang'  => $barang,
            'pending' => $this->permintaanModel->countPending(),
        ]);
    }

    public function update($id)
    {
        $barang = $this->model->find($id);
        if (!$barang) {
            return redirect()->to('/admin/master-alat-kantor')->with('error', 'Barang tidak ditemukan!');
        }

        $rules = [
            'nama_barang'           => 'required|min_length[2]|max_length[150]',
            'batas_pengajuan_ulang' => 'required|integer|greater_than_equal_to[0]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $nama = trim($this->request->getPost('nama_barang'));
        $existing = $this->model->where('nama_barang', $nama)->where('id !=', $id)->first();
        if ($existing) {
            return redirect()->back()->withInput()->with('error', 'Nama barang "' . $nama . '" sudah digunakan!');
        }

        $this->model->update($id, [
            'nama_barang'           => $nama,
            'batas_pengajuan_ulang' => (int) $this->request->getPost('batas_pengajuan_ulang'),
        ]);

        return redirect()->to('/admin/master-alat-kantor')->with('success', 'Barang berhasil diperbarui!');
    }

    public function hapus($id)
    {
        $barang = $this->model->find($id);
        if (!$barang) {
            return redirect()->to('/admin/master-alat-kantor')->with('error', 'Barang tidak ditemukan!');
        }
        $this->model->delete($id);
        return redirect()->to('/admin/master-alat-kantor')->with('success', 'Barang "' . $barang['nama_barang'] . '" berhasil dihapus!');
    }

    public function tambahStok($id)
    {
        $barang = $this->model->find($id);
        if (!$barang) {
            return redirect()->to('/admin/master-alat-kantor')->with('error', 'Barang tidak ditemukan!');
        }
        return view('admin/master_alat_kantor/tambah_stok', [
            'title'   => 'Tambah Stok — ' . $barang['nama_barang'],
            'barang'  => $barang,
            'pending' => $this->permintaanModel->countPending(),
        ]);
    }

    public function simpanStok($id)
    {
        $barang = $this->model->find($id);
        if (!$barang) {
            return redirect()->to('/admin/master-alat-kantor')->with('error', 'Barang tidak ditemukan!');
        }

        $rules = [
            'jumlah'     => 'required|integer|greater_than[0]',
            'keterangan' => 'permit_empty|max_length[255]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $jumlah     = (int) $this->request->getPost('jumlah');
        $keterangan = $this->request->getPost('keterangan') ?: 'Penambahan stok oleh admin';
        $stokBaru   = $this->model->tambahStok($id, $jumlah, session()->get('id_user'), session()->get('nama'), $keterangan);

        return redirect()->to('/admin/master-alat-kantor')
            ->with('success', 'Stok "' . $barang['nama_barang'] . '" berhasil ditambah. Stok sekarang: ' . $stokBaru);
    }

    public function riwayat($id = null)
    {
        $db      = \Config\Database::connect();
        $builder = $db->table('riwayat_stok');

        if ($id) {
            $barang = $this->model->find($id);
            if (!$barang) {
                return redirect()->to('/admin/master-alat-kantor')->with('error', 'Barang tidak ditemukan!');
            }
            $builder->where('barang_id', $id);
            $judulBarang = $barang['nama_barang'];
        } else {
            $judulBarang = 'Semua Barang';
        }

        return view('admin/master_alat_kantor/riwayat', [
            'title'       => 'Riwayat Stok — ' . $judulBarang,
            'riwayat'     => $builder->orderBy('created_at', 'DESC')->get()->getResultArray(),
            'judulBarang' => $judulBarang,
            'barang_id'   => $id,
            'pending'     => $this->permintaanModel->countPending(),
        ]);
    }

    public function getStok($id)
    {
        $barang = $this->model->find($id);
        if (!$barang) return $this->response->setJSON(['error' => 'Barang tidak ditemukan']);
        return $this->response->setJSON([
            'id'                    => $barang['id'],
            'nama_barang'           => $barang['nama_barang'],
            'stok'                  => $barang['stok'],
            'batas_pengajuan_ulang' => $barang['batas_pengajuan_ulang'],
        ]);
    }

    public function listBarang()
    {
        return $this->response->setJSON($this->model->getAllOrderedByName());
    }
}
